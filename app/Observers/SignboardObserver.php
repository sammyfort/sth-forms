<?php

namespace App\Observers;


use App\Models\Signboard;
use App\Services\GhanaPostService;

class SignboardObserver
{
    public function creating(Signboard $signboard): void
    {
        // get lat and lon from gps(Ghana post)
//        $location = GhanaPostService::getLocationByGPS($signboard->gps);
//        $signboard->gps_lat = $location->centerLongitude;
//        $signboard->gps_lon = $location->centerLatitude;
    }

    public function created(Signboard $signboard): void
    {
        //
    }

    public function updated(Signboard $signboard): void
    {
        if ($signboard->isDirty('gps')){
            $location = GhanaPostService::getLocationByGPS($signboard->gps);
            $signboard->gps_lat = $location->centerLongitude;
            $signboard->gps_lon = $location->centerLatitude;
            $signboard->save();
        }
    }

    public function deleted(Signboard $signboard): void
    {
        //
    }

    public function restored(Signboard $signboard): void
    {
        //
    }

    public function forceDeleted(Signboard $signboard): void
    {
        //
    }
}
