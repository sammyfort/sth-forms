<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Service;
use App\Models\Signboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $services = Service::query()
            ->with(['user', 'region'])
            ->with('media', function ($builder){
                $builder->where('collection_name', 'featured');
            })
            ->paginate();
        $services->map(function (Service $service){
            $service->featured = $service->getFirstMedia('featured');
        });
        return Inertia::render('Services', [
            'services' => $services
        ]);
    }

    public function getPromotedSignboards(): JsonResponse
    {
        $services = Service::query()
            ->with(['user', 'region'])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->inRandomOrder()
            ->take(10)
            ->get();

        $services->map(function (Service $service) {
            $service->featured = $service->getFirstMedia('featured');
        });
        return response()->success([
            'services' => $services
        ]);
    }
}
