<?php

namespace App\Http\Controllers\Auth;

use App\Enums\SocialAuth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SocialRegistrationPasswordNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function init(): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleCallBack(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $googleId = $googleUser->getId();
            $fullName = $googleUser->getName();
            $nameParts = explode(' ', $fullName, 2);
            $user = User::query()
                ->where('email', $googleUser->getEmail())
                ->where('google_id', $googleId)
                ->first();
            // register user
            if (!$user) {
                $password = Str::random(8);
                $user = User::query()->create([
                    'google_id' => $googleId,
                    'firstname' => $nameParts[0],
                    'lastname' => $nameParts[1] ?? "",
                    'password' => $password,
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => now(),
                ]);
                event(new Registered($user));
                $user->notify(new WelcomeNotification());
                $user->notify(new SocialRegistrationPasswordNotification($password, SocialAuth::Google));
                try {
                    $user->addMediaFromUrl($googleUser->getAvatar())->toMediaCollection('avatar');
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                };
            }

            // log user in
            Auth::login($user);
            $user->touch('last_login');
        } catch (\Exception $exception) {
            Log::error($exception);
            dd($exception);
        }

        // finally
        return to_route('home');
    }
}
