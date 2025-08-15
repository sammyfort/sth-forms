<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductService
{
    public static function getProducts(): LengthAwarePaginator
    {
        $productsQuery = QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::callback('q', function (Builder $query, $input) {
                    if (is_array($input)){
                        $input = implode(',', $input);
                    }
                    $query->where("name", "LIKE", "%$input%")
                        ->orWhere("town", "LIKE", "%$input%")
                        ->orWhere("first_mobile", "LIKE", "%$input%")
                        ->orWhere("second_mobile", "LIKE", "%$input%")
                        ->orWhereRelation('user', 'firstname', "LIKE", "%$input%")
                        ->orWhereRelation('user', 'lastname', "LIKE", "%$input%");
                }),
                AllowedFilter::callback('categories', function ($query, $value) {
                    $ids = is_array($value) ? $value : explode(',', $value);
                    $query->whereHas('categories', function ($q) use ($ids) {
                        $q->whereIn('product_categories.id', $ids);
                    });
                }),
                AllowedFilter::callback('price', function ($query, $value) {
                    $prices = is_array($value) ? $value : explode(',', $value);
                    $query->whereBetween('price', $prices);
                }),
                AllowedFilter::exact('country', 'region.country_id'),
                AllowedFilter::exact('region', 'region_id'),
            ])
            ->withCount('views')
            ->with(['region.country', 'user'])
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            })
            ->when(auth()->user(), function ($q){
                $q->where('user_id', '!=', auth()->id());
            });

        $products = CountryBuilderService::applyRegionFilterAuth($productsQuery);

        return $products;
    }

    public static function getPromoted(): Collection
    {
        $productsQuery = Product::getRandomPromotedQuery()
            ->with(['user', 'region.country'])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            });
        $products = CountryBuilderService::applyRegionFilterAuthOnPromoted($productsQuery);

        if ($products->count() < 1) {
            $productsQuery = Product::query()
                ->with(['user', 'region.country'])
                ->with('categories', function ($categoriesQuery) {
                    $categoriesQuery->take(3);
                })
                ->with('reviews', function ($reviewsQuery) {
                    $reviewsQuery->where('user_id', auth()->id())
                        ->with(['ratings']);
                })
                ->when(auth()->user(), function ($q){
                    $q->where('created_by_id', '!=', auth()->id());
                });
            $products = CountryBuilderService::applyRegionFilterAuthOnPromoted($productsQuery);
        }

        return $products;
    }
}
