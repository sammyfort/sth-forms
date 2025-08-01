<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Region;
use App\Services\RatingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductPublicController extends Controller
{
    public function index(): Response
    {
        $products = QueryBuilder::for(Product::class)
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
                AllowedFilter::exact('region', 'region_id'),
            ])

            ->withCount('views')
            ->with(['region', 'user'])
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            })
            // ->inRandomOrder()
            ->paginate()
            ->appends(request()->query());

        // get product with the highest price to set price filter slider
        $highestPrice = Product::query()
            ->orderBy('price', 'DESC')
            ->first(['price']);

        $regions = Region::query()->get(['id', 'name']);
        $categories = ProductCategory::query()->get(['id', 'name']);

        return Inertia::render('Products/Products', [
            'productsData' => $products,
            'regions' => $regions,
            'categories' => $categories,
            'highestPrice' => $highestPrice?->price ? (int) $highestPrice?->price : 100
        ]);
    }

    public function show(Product $product): Response
    {
        $product->loadMissing(['reviews.ratings', 'user', 'region', 'categories', 'media']);
        $distributions = RatingService::getDistributions($product);

        if (!auth() || auth()->id() != $product->user_id){
            $viewCooldown = now()->addHours(3);
            views($product)->cooldown($viewCooldown)->record();
        }

        $product->views_count = views($product)->count();

        $media = $product->media->toArray();

        return Inertia::render('Products/Product', [
            'product' => $product->toArrayWithMedia(),
            'reviews' => $product->reviews,
            'distributions' => $distributions,
            'media' => $media,
        ]);
    }

    public function getPromotedProducts(): JsonResponse
    {
        $products = Product::getRandomPromotedQuery()
            ->with(['user', 'region'])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            })->get();

        if ($products->count() < 1) {
            $products = Product::query()
                ->with(['user', 'region'])
                ->with('categories', function ($categoriesQuery) {
                    $categoriesQuery->take(3);
                })
                ->with('reviews', function ($reviewsQuery) {
                    $reviewsQuery->where('user_id', auth()->id())
                        ->with(['ratings']);
                })
                ->inRandomOrder()
                ->take(10)
                ->get();
        }

        return response()->success([
            'products' => $products
        ]);
    }
}
