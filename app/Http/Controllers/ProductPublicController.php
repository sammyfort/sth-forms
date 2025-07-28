<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Region;
use App\Services\RatingService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ProductPublicController extends Controller
{
    public function index(): Response
    {
        $products = QueryBuilder::for(Product::class)
            ->withCount('views')
            ->with(['region', 'user'])
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            })
            // ->inRandomOrder()
            ->paginate()
            ->appends(request()->query());

        $regions = Region::query()->get(['id', 'name']);
        $categories = ProductCategory::query()->get(['id', 'name']);

        return Inertia::render('Products/Products', [
            'productsData' => $products,
            'regions' => $regions,
            'categories' => $categories,
        ]);
    }

    public function show(Product $product): Response
    {
        $product->loadMissing(['reviews.ratings', 'user', 'region', 'categories', 'media']);
        $averageRatings = $product->averageRatings();
        $distributions = RatingService::getDistributions($product);

        if (!auth() || auth()->id() != $product->user_id){
            $viewCooldown = now()->addHours(3);
            views($product)->cooldown($viewCooldown)->record();
        }

        $product->views_count = views($product)->count();

        return Inertia::render('Products/Product', [
            'product' => $product->toArrayWithMedia(),
            'ratings' => $averageRatings,
            'distributions' => $distributions,
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
