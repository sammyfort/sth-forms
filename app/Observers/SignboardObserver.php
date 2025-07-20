<?php

namespace App\Observers;


use App\Models\Signboard;
use App\Services\GhanaPostService;

class SignboardObserver
{
    public function creating(Signboard $signboard): void
    {
        if (empty($signboard->slug)) {
            $signboard->slug = rand(11111111, 99999999);
        }
        // get lat and lon from gps(Ghana post)
//        $location = GhanaPostService::getLocationByGPS($signboard->gps);
//        $signboard->gps_lat = $location->centerLongitude;
//        $signboard->gps_lon = $location->centerLatitude;
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
        if ($signboard->isDirty('gps')){
            $location = GhanaPostService::getLocationByGPS($signboard->gps);
            $signboard->gps_lat = $location->centerLongitude;
            $signboard->gps_lon = $location->centerLatitude;
            $signboard->save();
        }
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
