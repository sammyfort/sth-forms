<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\Promotable;
use App\Http\Requests\PromotionRequest;
use App\Models\PromotionPlan;
use App\Services\HubtelService;
use Illuminate\Support\Str;
use \Illuminate\Http\RedirectResponse;

class PromotionController extends Controller
{
    private HubtelService $hubtelService;

    public function __construct()
    {
        $this->hubtelService = new HubtelService();
    }

    public function initializeHubtel(PromotionRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $user = auth()->user();
        $plan = PromotionPlan::query()->find($validatedData['plan_id']);
        $reference = Str::uuid()->toString();
        $promotableEnum = Promotable::from($validatedData['promotable_type']);
        $promotableModel = Promotable::getModel($promotableEnum);
        $promotable = $promotableModel::query()->find($validatedData['signboard_id'], ['id', 'slug']);
        if (! $promotable) {
            return back()->with(errorRes());
        }
        $promotableRoute = Promotable::getRoute($promotable, $promotable->slug)."?reference=$reference";
        $redirectUrl = route('promotions.payment.verify');

        $data = [
            "totalAmount" => $plan->price,
            "payeeName" => $user->fullname,
            "payeeMobileNumber" => $user->mobile,
            "payeeEmail" => $user->email,
            "description" => "Alexoa promotion subscription",
            "callbackUrl" => $redirectUrl,
            "returnUrl" => "$promotableRoute&payment_status=pending",
            "merchantAccountNumber" => config('app.hubtel_account_number'),
            "cancellationUrl" => "$promotableRoute&payment_status=cancelled",
            "clientReference" => $reference
        ];

        $payment = $this->hubtelService->initializePayment($data);

        if ($payment){
            $promotable->promotions()->create([
                'plan_id' => $plan->id,
                'payment_reference' => $reference,
                'checkout_id' => $payment['checkoutId'],
                'payment_status' => PaymentStatus::PENDING,
                'amount' => $plan->price,
                'payment_platform' => 'Hubtel',
            ]);
        }

        return back()->with(successRes('Url generated', ['authorization_url' => $payment['checkoutUrl']]));
    }
}
