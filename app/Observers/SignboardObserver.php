<?php

namespace App\Observers;


use App\Models\Signboard;

class SignboardObserver
{
    public function creating(Signboard $business): void
    {
        if (empty($business->slug)) {
            $business->slug = rand(11111111, 99999999);
        }
    }

    /**
     * Handle the Signboard "created" event.
     */
    public function created(Signboard $signboard): void
    {
        //
    }

    /**
     * Handle the Signboard "updated" event.
     */
    public function updated(Signboard $signboard): void
    {
        //
    }

    /**
     * Handle the Signboard "deleted" event.
     */
    public function deleted(Signboard $signboard): void
    {
        //
    }

    /**
     * Handle the Signboard "restored" event.
     */
    public function restored(Signboard $signboard): void
    {
        //
    }

    /**
     * Handle the Signboard "force deleted" event.
     */
    public function forceDeleted(Signboard $signboard): void
    {
        //
    }
}
