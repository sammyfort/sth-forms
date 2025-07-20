<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $services = QueryBuilder::for(Service::class)
            ->allowedFilters([
                AllowedFilter::callback('q', function (Builder $query, $input) {
                    $query->where("title", "LIKE", "%$input%")
                        ->orWhere("business_name", "LIKE", "%$input%")
                        ->orWhere("address", "LIKE", "%$input%")
                        ->orWhere("town", "LIKE", "%$input%")
                        ->orWhere("gps", "LIKE", "%$input%");
                }),
                AllowedFilter::callback('categories', function (Builder $query, $value) {
                    $ids = is_array($value) ? $value : explode(',', $value);
                    $query->whereIn('category_id', $ids);
                }),
                AllowedFilter::exact('region', 'region_id'),
            ])
            ->with('media', function ($builder){
                $builder->where('collection_name', 'featured');
            })
            ->with(['region', 'user'])
//            ->inRandomOrder()
            ->paginate(8)
            ->appends(request()->query());

        $services->map(function (Service $service){
            $service->featured = $service->getFirstMedia('featured');
        });

        return Inertia::render('Services/Services', [
            'servicesData' => $services,
            'categories' => ServiceCategory::query()->get(['id', 'name']),
            'regions' => Region::query()->get(['id', 'name']),
        ]);
    }

    public function getPromotedSignboards(): JsonResponse
    {
        $services = Service::query()
            ->with(['user', 'region', 'category'])
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

    public function show(Service $service): Response
    {

        return Inertia::render('Services/Service', [
            'service' => $service
        ]);
    }
}
