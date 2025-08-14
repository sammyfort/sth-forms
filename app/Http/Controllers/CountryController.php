<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        $data['countries'] = Country::query()
            ->whereHas('regions')
            ->get(['name', 'id', 'phonecode']);
        return $this->apiSuccessResponse($data);
    }

    public function regions(): JsonResponse
    {
        $cid = request()->get('cid');

        $regions = Region::query()
            ->when($cid, function ($query, $cid) {
                $query->where('country_id', $cid);
            })
            ->get(['name', 'id']);

        $data['regions'] = $regions;

        return $this->apiSuccessResponse($data);
    }
}
