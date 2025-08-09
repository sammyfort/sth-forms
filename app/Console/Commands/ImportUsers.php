<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:u';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from old database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = include database_path('import/j_users.php');

        DB::transaction(function () use ($users) {
            $insert = [];
            foreach ($users as $user) {

                $mobile = trim($user['j_phone'] ?? '');
                $email = trim($user['j_email'] ?? '');

                if (strlen(trim($user['j_firstname'])) > 0){
                    $insert[] = [
                        'uuid' => Str::uuid(),
                        'old_id' => $user['j_id'],
                        'firstname' => $user['j_firstname'],
                        'lastname' => $user['j_lastname'],
                        'email' => $email,
                        'mobile' => $mobile,
                        'password' => Hash::make('123456'),
                        'email_verified_at' => now(),
                        'referral_code' => Str::random(10),
                    ];
                }
            }

            $insertFinal = array_values(array_reduce($insert, function ($carry, $item) {
                static $seenEmails = [];
                static $seenMobiles = [];

                if (!in_array($item['email'], $seenEmails) && !in_array($item['mobile'], $seenMobiles)) {
                    $carry[] = $item;
                    $seenEmails[] = $item['email'];
                    $seenMobiles[] = $item['mobile'];
                }

                return $carry;
            }, []));

            User::query()->fillAndInsert($insertFinal);
            $this->info('users imported');
        });
    }
}
