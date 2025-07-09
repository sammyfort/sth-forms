<?php

namespace App\Services;

use App\Models\Region;
use App\Models\SignboardCategory;
use Illuminate\Support\Collection;

class HelperService
{

    public function getRegions(): Collection
    {
        return Region::query()
            ->select(['id', 'name'])
            ->get()
            ->map(fn($region) => [
                'label' => $region->name,
                'value' => (string) $region->id,
            ]);


    }

    public function getCategories(): Collection
    {
        return SignboardCategory::select(['id', 'name'])
            ->get()
            ->map(fn($cat) => [
                'label' => $cat->name,
                'value' => $cat->id,
            ]);
    }


    public function getAuthBusinesses(): Collection
    {
        return request()->user()->businesses()
            ->select('id', 'name')
            ->get()
            ->map(fn($business) => [
                'label' => $business->name,
                'value' => (string) $business->id,
            ]);

    }




}
