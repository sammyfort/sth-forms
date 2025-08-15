<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Region;
use App\Services\ProductService;
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
        $products = ProductService::getProducts();

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
        $product->loadMissing(['reviews.ratings', 'user', 'region.country', 'categories', 'media']);

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
        $products = ProductService::getPromoted();

        return response()->success([
            'products' => $products
        ]);
    }
}
