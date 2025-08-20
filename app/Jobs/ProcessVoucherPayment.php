<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Voucher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

class ProcessVoucherPayment
{
    use Queueable;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $payload)
    {
        //
    }

    /**
     * Execute the job.
     * @throws Throwable
     */
    public function handle(): void
    {
        $payload = $this->payload;
        if (isset($payload['status']) && isset($payload['metadata']['user_id'])) {

            $transaction = Voucher::query()->where('payment_reference',$payload['reference'])->first();
            if (!$transaction){
                if ($payload['status'] === 'success'){
                    $category = Category::query()->find($payload['metadata']['category_id'])->first();
                    if ($category){
                        $data = [
                            'category_id' => $payload['metadata']['category_id'],
                            'amount_paid' => $payload['metadata']['amount'],
                            'payment_reference' => $payload['reference'],
                            'payment_channel' => $payload['authorization']['bank'],
                            'payment_status' => $payload['status'],

                            'full_name' => $payload['metadata']['full_name'],
                            'email' => $payload['metadata']['email'],
                            'phone' => $payload['metadata']['phone'],

                            'code' => rand(000000000000,999999999999),
                        ];

                        DB::beginTransaction();
                        try {
                            $voucher = Voucher::query()->create($data);
                            DB::commit();
                            $this->sendPaymentSMS($voucher);
                            $this->sendPaymentEmail($voucher);
                        }catch (\Exception $exception){
                            DB::rollBack();
                        }
                    }
                }
            }

        }
    }


    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    protected function sendPaymentSMS(Voucher $voucher): void
    {

        $key = config('services.velstack.SENDER_ID');
        $sender = config('services.velstack.SENDER_ID');
        $data = [
            'title' => 'SMS Notifications',
            'recipient' => $voucher->phone,
            'sender' => $sender,
            'message' => "Hello $voucher->full_name, your voucher code to complete your application is $voucher->code. Thank you",
            'is_scheduled' => false,
        ];
        $response = Http::withHeaders([
            'Authorization' => "Bearer $key",
            'Accept' => 'application/json',
        ])->post('https://sms.velstack.com/api/quick/sms', $data);

        $response->throw();
    }

    protected function sendPaymentEmail(Voucher $voucher)
    {

    }
}
