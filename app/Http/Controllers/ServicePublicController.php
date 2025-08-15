<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\CountryBuilderService;
use App\Services\RatingService;
use App\Services\ServiceResService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServicePublicController extends Controller
{
    public function index(): Response
    {
        $services = ServiceResService::getServices();
        $regions = Region::query()->get(['id', 'name']);
        $categories = ServiceCategory::query()->get(['id', 'name']);

        return Inertia::render('Services/Services', [
            'servicesData' => $services,
            'regions' => $regions,
            'categories' => $categories,
        ]);
    }

    public function show(Service $service): Response
    {
        $service->loadMissing(['user', 'category', 'region', 'reviews.ratings'])
            ->loadCount('views');

        if (!auth() || auth()->id() != $service->user_id){
            $viewCooldown = now()->addHours(3);
            views($service)->cooldown($viewCooldown)->record();
        }
        $service->views_count = views($service)->count();

        $distributions = RatingService::getDistributions($service);
        $averageRatings = $service->averageRatings();

        return Inertia::render('Services/Service', [
            'service' => $service,
            'distributions' => $distributions,
            'ratings' => $averageRatings,
        ]);
    }

    public function getPromotedSignboards(): JsonResponse
    {
        $services = ServiceResService::getPromoted();

        return response()->success([
            'services' => $services
        ]);
    }
}
