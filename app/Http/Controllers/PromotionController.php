<?php

namespace App\Http\Controllers;

use App\DTO\PromotionDTO;
use App\Enums\PaymentStatus;
use App\Enums\Promotable;
use App\Http\Requests\PromotionRequest;
use App\Http\Requests\SignboarbSubscriptionRequest;
use App\Models\PromotionPlan;
use App\Models\Signboard;
use App\Models\SignboardSubscription;
use App\Models\SignboardSubscriptionPlan;
use App\Services\HubtelService;
use App\Services\PaystackService;
use Illuminate\Support\Facades\Log;
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
            $promotion = new PromotionDTO($reference);
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
}
