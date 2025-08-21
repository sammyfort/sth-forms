<?php

namespace App\Jobs;

use App\Models\ResearchApplication;
use App\Services\Velstack;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class ResearchFormSubmitted
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected ResearchApplication $application)
    {
        //
    }

    /**
     * Execute the job.
     * @throws ConnectionException
     * @throws RequestException
     */
    public function handle(): void
    {
        $message = "Your research application has successfully been submitted. Thank you";
        (new Velstack())->sendQuickSMS($this->application->telephone, $message);

        // send mail
    }
}
