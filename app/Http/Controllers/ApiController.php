<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Signboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ApiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['getAuthBusinesses']),
        ];
    }

    public function __invoke(Request $request, string $path)
    {
        return match ($path) {
            'regions' => $this->getRegions(),
            'authBusinesses' => $this->getAuthBusinesses($request),
            default => response()->error('Invalid route path')
        };
    }

    public function getRegions(): JsonResponse
    {
        $regions = Region::query()
            ->select(['id', 'name'])
            ->get()
            ->map(fn($region) => [
                'label' => $region->name,
                'value' => (string) $region->id,
            ]);

        return response()->success([
            'regions' => $regions
        ]);
    }


    public function getAuthBusinesses(Request $request): JsonResponse
    {
       $businesses = $request->user()->businesses()
            ->select('id', 'name')
            ->get()
            ->map(fn($business) => [
                'label' => $business->name,
                'value' => (string) $business->id,
            ]);
       return response()->success([
           'businesses' => $businesses
       ]);
    }
}
