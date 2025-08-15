<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceResService
{
    public static function getServices(): LengthAwarePaginator
    {
        $servicesQuery = QueryBuilder::for(Service::class)
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
                AllowedFilter::exact('country', 'region.country_id'),
                AllowedFilter::exact('region', 'region_id'),
            ])
            ->where('user_id', '!=', auth()->id())
            ->with('media', function ($builder){
                $builder->where('collection_name', 'featured');
            })
            ->withCount('views')
            ->with(['region', 'user', 'category'])
            ->when(auth()->user(), function ($q){
                $q->where('user_id', '!=', auth()->id());
            });

        $services = CountryBuilderService::applyRegionFilterAuth($servicesQuery);

        $services->map(function (Service $service){
            $service->featured = $service->getFirstMedia('featured');
        });
        return $services;
    }

    public static function getPromoted(): Collection
    {
        $servicesQuery = Service::getRandomPromotedQuery()
            ->with(['user', 'region', 'category',])
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            });
        $services = CountryBuilderService::applyRegionFilterAuthOnPromoted($servicesQuery);

        if ($services->count() < 1){
            $servicesQuery = Service::query()
                ->with(['user', 'region', 'category'])
                ->with('reviews', function ($reviewsQuery) {
                    $reviewsQuery->where('user_id', auth()->id())
                        ->with(['ratings']);
                })
                ->when(auth()->user(), function ($q){
                    $q->where('created_by_id', '!=', auth()->id());
                });
            $services = CountryBuilderService::applyRegionFilterAuthOnPromoted($servicesQuery);
        }

        $services->map(function (Service $service) {
            $service->featured = $service->getFirstMedia('featured');
        });

        return $services;
    }
}
