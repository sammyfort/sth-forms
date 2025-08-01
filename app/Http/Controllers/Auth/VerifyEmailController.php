<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\ReferrerRewardNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            $user = $request->user();
            event(new Verified($user));

            // reward referrer
            $referrer = $user->referrer;
            if ($referrer){
                if (!$user->is_referrer_points_settled){
                    // reward point if reward has not already been given
                    $referrer->update([
                        'points' => ($referrer->points + config('app.points_per_referral'))
                    ]);
                    // notify referrer
                    $referrer->notify(new ReferrerRewardNotification($user));

                    // mark is_referrer_points_settled as true
                    $user->update([
                        'is_referrer_points_settled' => true
                    ]);
                }
            }
        }

        return redirect()->intended(route('home', absolute: false).'?verified=1');
    }
}
