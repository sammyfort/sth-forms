<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
                $user = User::query()->create([
                    'google_id' => $googleId,
                    'firstname' => $nameParts[0],
                    'lastname' => $nameParts[1] ?? "",
                    'password' => "google-user-{$googleId}",
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => now(),
                ]);
                event(new Registered($user));
                $user->notify(new WelcomeNotification());
                try {
                    $user->addMediaFromUrl($googleUser->getAvatar())->toMediaCollection('avatar');
                } catch (\Exception $exception) {
                    dd($exception->getMessage());
                };
            }

            // log user in
            Auth::login($user);
            $user->touch('last_login');
        } catch (\Exception $exception) {}

        // finally
        return to_route('home');
    }
}
