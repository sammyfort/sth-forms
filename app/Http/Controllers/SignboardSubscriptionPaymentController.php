<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Http\Requests\SignboarbSubscriptionRequest;
use App\Models\Signboard;
use App\Models\SignboardSubscription;
use App\Models\SignboardSubscriptionPlan;
use App\Services\PaystackService;
use Illuminate\Support\Str;

class SignboardSubscriptionPaymentController extends Controller
{
    private PaystackService $paystackService;

    public function __construct()
    {
        $this->paystackService = new PaystackService();
    }

    public function initialize(SignboarbSubscriptionRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();
        $plan = SignboardSubscriptionPlan::query()->find($validatedData['plan_id']);
        $reference = Str::uuid()->toString();
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

    public function verify(): \Illuminate\Http\RedirectResponse
    {
        $reference = request('reference');
        if (!$reference) abort(404);
        $payment = $this->paystackService->verifyPayment($reference);

        $signboard = Signboard::query()->find($payment?->metadata?->signboard_id);

        if ($signboard){
            if ($payment && $payment->status) {
                $plan = SignboardSubscriptionPlan::query()->find($payment->metadata->plan_id);
                $subscription = $signboard?->subscriptions()->where('payment_reference', $reference)->first() ?? null;

                if ($subscription && $plan) {
                    $amount = $payment->amount / 100;
                    if ($plan->price <= $amount) {
                        $now = now();

                        // TODO:: Check if signboard has existing subscription, then add days from $plan
                        // end all existing subscriptions
                        $exSubs = $signboard->subscriptions()
                            ->where('id', '!=', $subscription->id)
                            ->whereDate('ends_at', '<=', $now)
                            ->get();
                        foreach ($exSubs as $exSub) {
                            $exSub->update([
                                'ends_at' => $now,
                            ]);
                        }

                        $subscription->update([
                            'payment_channel' => $payment->channel,
                            'receipt_number' => $payment->receipt_number,
                            'amount' => $plan->price,
                            'starts_at' => $now,
                            'ends_at' => $now->addDays($plan->number_of_days),
                            'payment_status' => PaymentStatus::PAID,
                        ]);
                        // redirect back to the signboard show page, with payment successful alert
                        return redirect(route('my-signboards.show', $signboard)."?payment_status=success");
                    }
                }
            }
            return redirect(route('my-signboards.show', $signboard)."?payment_status=failed");
        }
        abort(404);
    }
}
