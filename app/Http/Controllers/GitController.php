<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GitController extends Controller
{
    public function __invoke(Request $request)
    {
        $secret = env('GIT_SECRET');
        $branch_name = env('GIT_BRANCH_NAME');
        $path = env('GIT_PROJECT_BASE_PATH');
        $branch = "refs/heads/$branch_name";

        // Get the payload
        $payload = $request->getContent();
        $sigHeader = $request->header('X-Hub-Signature-256');
        $sig = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        // Verify the signature
        if (hash_equals($sig, $sigHeader)) {
            $data = json_decode($payload);
            if ($data){
                if ($data->ref === $branch) {
                    try {
                        // Set HOME and COMPOSER_HOME environment variables
                        $home = getenv('HOME') ?: '/root';
                        putenv("HOME={$home}");
                        putenv("COMPOSER_HOME={$home}");
                        // Run the shell commands
                        $composerPath = '/opt/cpanel/composer/bin/composer';
                        $process = new Process(['sh', '-c', "cd {$path} && export HOME={$home} && export COMPOSER_HOME={$home} && git fetch --all && git reset --hard origin/$branch_name && $composerPath install --ignore-platform-reqs && php artisan migrate --force && php artisan optimize:clear"]);
                        $process->run();

                        // Check if there were any errors
                        if (!$process->isSuccessful()) {
                            throw new ProcessFailedException($process);
                        }
                        return response('Success', 200);
                    } catch (\Exception $e) {
                        // Log the error for debugging
//                        Log::error('Webhook execution failed', ['error' => $e->getMessage()]);
                        return response('Internal Server Error', 500);
                    }
                }
            }
        } else {
            return response('Error: Invalid signature', 403);
        }
    }
}
