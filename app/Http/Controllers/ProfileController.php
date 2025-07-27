<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePersonalDetailsRequest;
use App\Http\Requests\Profile\UpdateSocialsRequest;
use App\Notifications\PasswordChangedNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function show(): Response
    {
        $referrals = auth()->user()->referrals()
            ->get(['id', 'firstname', 'lastname', 'mobile', 'created_at'])
            ->map(function ($user){
                $user->created_at_dh = Carbon::parse($user->created_at)->diffForHumans();
                return $user;
            });

        return Inertia::render('Profile/Show', [
            'referrals' => $referrals
        ]);
    }

    public function editPersonalDetails(UpdatePersonalDetailsRequest $request): RedirectResponse
    {
        try {
            $request->user()->fill($request->validated());
            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }
            $request->user()->save();
            return back()->with(successRes("Personal Details updated successfully"));
        } catch (\Exception $exception){
            return back()->with(errorRes());
        }
    }

    public function editSocials(UpdateSocialsRequest $request): RedirectResponse
    {
        try {
            $request->user()->fill($request->validated())->save();
            return back()->with(successRes("Socials updated successfully"));
        } catch (\Exception $exception){
            return back()->with(errorRes());
        }
    }

    public function uploadAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);
        try {
            uploadToGallery(Auth::user(), $request->file('avatar'), 'avatar');
            return back()->with(successRes("Avatar uploaded successfully"));
        }
        catch (\Exception $exception){
            return back()->with(errorRes());
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        try {
            $user = $request->user();
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
            $user->notify(new PasswordChangedNotification());
            return back()->with(successRes("Your password has been changed successfully"));
        }
        catch (\Exception $exception){
            return back()->with(errorRes());
        }
    }
}
