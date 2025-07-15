<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Http\Requests\SignboarbSubscriptionRequest;
use App\Models\Signboard;
use App\Models\SignboardSubscription;
use App\Models\SignboardSubscriptionPlan;
use App\Services\HubtelService;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function Pest\Laravel\json;

class SignboardSubscriptionPaymentController extends Controller
{
    private PaystackService $paystackService;
    private HubtelService $hubtelService;

    public function __construct()
    {
        $this->paystackService = new PaystackService();
        $this->hubtelService = new HubtelService();
    }

    public function initializeHubtel(SignboarbSubscriptionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();
        $plan = SignboardSubscriptionPlan::query()->find($validatedData['plan_id']);
        $reference = Str::uuid()->toString();
        $signboard = Signboard::query()->find($validatedData['signboard_id'], ['id', 'slug']);
        $signboardRoute = route('my-signboards.show', $signboard->slug)."?reference=$reference";
        $redirectUrl = route('payments.signboard-subscription.verify');

        $data = [
            "totalAmount" => $plan->price,
            "description" => "Signboard test",
            "callbackUrl" => $redirectUrl,
            "returnUrl" => "$signboardRoute&payment_status=pending",
            "merchantAccountNumber" => "2030753",
            "cancellationUrl" => "$signboardRoute&payment_status=cancelled",
            "clientReference" => $reference
        ];

        Log::info($redirectUrl);

        $payment = $this->hubtelService->initializePayment($data);

        if ($payment){
            SignboardSubscription::query()->create([
                'signboard_id' => $validatedData['signboard_id'],
                'plan_id' => $validatedData['plan_id'],
                'payment_reference' => $reference,
                'checkout_id' => $payment['checkoutId'],
                'payment_status' => PaymentStatus::PENDING,
                'amount' => $plan->price,
                'payment_platform' => 'Hubtel',
            ]);
        }

        return back()->with(successRes('Url generated', ['authorization_url' => $payment['checkoutUrl']]));
    }

    public function verifyHubtel(Request $request): mixed
    {
        Log::info('Hubtel Webhook Received', $request->all());

        $payload = $request->all();

        if (!isset($payload['ResponseCode']) || $payload['ResponseCode'] != '0000') {
            Log::warning('Invalid Hubtel webhook response code', $payload);
            return response('', 200);
        }

        $data = $payload['Data'] ?? [];

        $reference = $data['ClientReference'] ?? null;
        $checkoutId = $data['CheckoutId'] ?? null;
        $status = strtolower($data['Status'] ?? '');
        $amount = (float) ($data['Amount'] ?? 0);
        $channel = $data['PaymentDetails']['Channel'] ?? null;

        if (!$reference || !$status || !$amount) {
            Log::warning('Incomplete Hubtel webhook data', $data);
            return response('', 200);
        }

        // Find subscription using the payment reference
        $subscription = SignboardSubscription::query()
//            ->where('payment_status', PaymentStatus::PENDING)
            ->where('payment_reference', $reference)
            ->first();

        if (!$subscription) {
            Log::warning('Subscription not found for reference: ' . $reference);
            return response('', 200);
        }

        $signboard = $subscription->signboard;
        $plan = $subscription->plan;

        if (!$signboard || !$plan) {
            Log::warning('Missing signboard or plan for subscription', ['subscription_id' => $subscription->id]);
            return response('', 200);
        }

        if ($status === 'success' && $amount >= $plan->price) {
            $now = now();

            // Get existing subscriptions (excluding current one) that are still active
            $activeSubs = $signboard->subscriptions()
                ->where('id', '!=', $subscription->id)
                ->where('payment_status', PaymentStatus::PAID)
                ->where('ends_at', '>', $now)
                ->latest()
                ->get();

            $arrearsDays = 0;

            DB::beginTransaction();
            try {
                if ($activeSubs->count()) {
                    $latest = $activeSubs->first(); // Just use the first one
                    $arrearsDays = max((int) $now->diffInDays(Carbon::parse($latest->ends_at)), 0);
                    // End all other subscriptions
                    foreach ($activeSubs as $old) {
                        $old->update(['ends_at' => $now]);
                    }
                }

                $totalDays = (int)(round($plan->number_of_days + $arrearsDays));
                $endsAt = now()->addDays(max($totalDays, 0));

                Log::info(json_encode([
                    'plan_days'    => $plan->number_of_days,
                    'arrears_days' => $arrearsDays,
                    'raw_sum'      => $plan->number_of_days + $arrearsDays,
                    'Ends At'      => $endsAt,
                ]));

                // Update this subscription
                $subscription->update([
                    'checkout_id'     => $checkoutId,
                    'payment_channel' => $channel,
                    'starts_at'       => $now,
                    'ends_at'         => $endsAt,
                    'payment_status'  => PaymentStatus::PAID,
                ]);

                Log::info(json_encode($subscription->toArray()));

                Log::info('Subscription marked as PAID', ['reference' => $reference]);

                DB::commit();
            }
            catch (\Exception $e) {}
        }
        else {
            // Mark failed
            $subscription->update([
                'payment_status' => PaymentStatus::FAILED,
            ]);
            Log::info('Payment failed or amount mismatch', ['reference' => $reference]);
        }

        return response('', 200);
    }



















    public function initializePaystack(SignboarbSubscriptionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();
        $plan = SignboardSubscriptionPlan::query()->find($validatedData['plan_id']);
        $reference = Str::uuid()->toString();
        $signboardRoute = route('my-signboards.show', $validatedData['signboard_id']);
        $data = [
            'reference' => $reference,
            'email' => auth()->user()->email,
            'amount' => $plan->price * 100,
            'callback_url' => route('payments.signboard-subscription.verify'),
            'metadata' => [
                "signboard_id" => $validatedData['signboard_id'],
                "plan_id" => $validatedData['plan_id'],
            ],
        ];

        $paymentUrl = $this->paystackService->initializePayment($data);
        if ($paymentUrl){
            SignboardSubscription::query()->create([
                'signboard_id' => $validatedData['signboard_id'],
                'plan_id' => $validatedData['plan_id'],
                'payment_reference' => $reference,
                'payment_status' => PaymentStatus::PENDING,
            ]);
        }
        return back()->with(successRes('Url generated', ['authorization_url' => $paymentUrl]));
    }

    public function verifyPaystack(): \Illuminate\Http\RedirectResponse
    {
        $reference = request('reference');
        if (!$reference) abort(404);
        $payment = $this->paystackService->verifyPayment($reference);
        $signboard = Signboard::query()->find($payment?->metadata?->signboard_id);
        $subscription = $signboard?->subscriptions()->where('payment_reference', $reference)->first() ?? null;

        if ($signboard){
            if ($payment && $payment->status) {
                $plan = SignboardSubscriptionPlan::query()->find($payment->metadata->plan_id);
                if ($subscription && $plan) {
                    $amount = $payment->amount / 100;
                    if ($plan->price <= $amount) {
                        $now = now();
                        // get existing active subscription if there is any
                        // Add the remaining days to the new subscription
                        $exSubs = $signboard->subscriptions()
                            ->where('id', '!=', $subscription->id)
                            ->whereDate('ends_at', '>=', $now)
                            ->get();
                        $exSub = $exSubs->first();

                        $arrearsDays = 0;

                        if ($exSub){
                            $arrearsDays = Carbon::parse($exSub->ends_at)->diffInDays(Carbon::now());
                            // end all existing subscriptions
                            foreach ($exSubs as $exSub) {
                                $exSub->update([
                                    'ends_at' => $now,
                                ]);
                            }
                        }

                        $subscription->update([
                            'payment_channel' => $payment->channel,
                            'receipt_number' => $payment->receipt_number,
                            'amount' => $plan->price,
                            'starts_at' => $now,
                            'ends_at' => now()->addDays(($plan->number_of_days + $arrearsDays)),
                            'payment_status' => PaymentStatus::PAID,
                            'payment_platform' => "paystack",
                        ]);
                        // redirect back to the signboard show page, with payment successful alert
                        return redirect(route('my-signboards.show', $signboard)."?payment_status=success");
                    }
                }
            }
            $subscription?->update([
                'payment_status' => PaymentStatus::FAILED,
            ]);
            return redirect(route('my-signboards.show', $signboard)."?payment_status=failed");
        }
        abort(404);
    }
}
