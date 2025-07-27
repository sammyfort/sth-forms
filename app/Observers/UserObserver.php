<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user): void
    {
        $user->referral_code = Str::random(10);
    }

    public function created(User $user): void
    {
        //
    }

    public function updated(User $user): void
    {
        //
    }

    public function deleted(User $user): void
    {
        //
    }

    public function restored(User $user): void
    {
        //
    }

    public function forceDeleted(User $user): void
    {
        //
    }
}
