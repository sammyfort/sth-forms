<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        $referrer = null;
        if (request()->get('rfc')){
            $referrer = User::query()
                ->where('referral_code', request()->get('rfc'))
                ->first(['referral_code', 'id']);
        }
        return Inertia::render('auth/Register', [
            'rfc' => $referrer?->referral_code ?? null,
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $rfc = $validated['rfc'] ?? null;

        if ($rfc){
            // set the referrer ID
            $referrer = User::query()->where('referral_code', $rfc)->first();
            if ($referrer){
                $validated['referrer_id'] = $referrer->id;
            }
            unset($validated['rfc']);
        }

        $user = User::query()
            ->create($validated);

        event(new Registered($user));
        Auth::login($user);
        $user->touch('last_login');

        return to_route('verification.notice');
    }
}
