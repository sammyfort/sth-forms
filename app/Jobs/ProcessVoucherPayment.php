<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Voucher;
use App\Services\Velstack;
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
        $message = "Hello $voucher->full_name, your voucher code to complete your application is $voucher->code. Thank you";
        (new Velstack())->sendQuickSMS($voucher->phone, $message);
    }

    protected function sendPaymentEmail(Voucher $voucher)
    {

    }
}
