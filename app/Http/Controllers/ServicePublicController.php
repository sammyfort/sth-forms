<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\RatingService;
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
        $services = QueryBuilder::for(Service::class)
            ->allowedFilters([
                AllowedFilter::callback('q', function (Builder $query, $input) {
                    if (is_array($input)){
                        $input = implode(',', $input);
                    }
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
            ->where('user_id', '!=', auth()->id())
            ->with('media', function ($builder){
                $builder->where('collection_name', 'featured');
            })
            ->withCount('views')
            ->with(['region', 'user', 'category'])
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
        $services = Service::getRandomPromotedQuery()
            ->with(['user', 'region', 'category',])
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            })->get();

        if ($services->count() < 1){
            $services = Service::query()
                ->with(['user', 'region', 'category'])
                ->when(auth()->user(), function ($q){
                    $q->where('created_by_id', '!=', auth()->id());
                })
                ->with('reviews', function ($reviewsQuery) {
                    $reviewsQuery->where('user_id', auth()->id())
                        ->with(['ratings']);
                })
                ->inRandomOrder()
                ->take(10)
                ->get();
        }

        $services->map(function (Service $service) {
            $service->featured = $service->getFirstMedia('featured');
        });

        return response()->success([
            'services' => $services
        ]);
    }
}
