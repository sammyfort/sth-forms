<?php

namespace App\Http\Controllers;

use App\Enums\YesNo;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Promotion;
use App\Models\PromotionPlan;
use App\Models\Region;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $sort = request('sort', 'desc');

        return Inertia::render('Products/MyProducts', [
            'products' => auth()->user()->products()
                ->with(['user',])
                ->orderBy('created_at', $sort)
                ->paginate(12)
                ->withQueryString(),
            'sort' => $sort,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $cats = ProductCategory::select(['id', 'name'])->get();
        $regions = Region::select(['id', 'name'])->get();
        return Inertia::render('Products/ProductCreate', [
            'regions' => toLabelValue($regions, 'name', 'id'),
            'choices' => toLabelValue(YesNo::toArray()),
            'categories' => toLabelValue($cats, 'name', 'id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_negotiable'] = YesNo::from($data['is_negotiable'])->toBool();

        DB::transaction(function () use ($data, $request) {
            $product = auth()->user()->products()->create(
                Arr::except($data, ['featured', 'gallery', 'categories'])
            );
            $product->categories()->sync($data['categories']);

            $product->handleUploads($request, [
                'featured' => 'featured',
                'gallery' => 'gallery',
            ]);
        });

        return back()->with(successRes("Product created successfully."));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $product): Response
    {
        $product = auth()->user()->products()->findOrFail($product);
        $product->views_count = views($product)->count();
        $plans = PromotionPlan::query()->get(['id', 'name', 'description', 'number_of_days', 'price']);
        $product = $product->loadMissing(['user', 'region', 'promotions.plan']);

        // check if it has payment
        $paymentStatus = Promotion::routeCallback();

        return Inertia::render('Products/ProductShow', [
            'product' => $product->toArrayWithMedia(),
            'plans' => $plans,
            'payment_status' => $paymentStatus,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $product): Response
    {
        $product = auth()->user()->products()->findOrFail($product);
        $cats = ProductCategory::select(['id', 'name'])->get();
        $regions = Region::select(['id', 'name'])->get();
        return Inertia::render('Products/ProductEdit', [
            'product' => $product->load(['user', 'region', 'categories'])->toArrayWithMedia(),
            'regions' => toLabelValue($regions, 'name', 'id'),
            'choices' => toLabelValue(YesNo::toArray()),
            'categories' => toLabelValue($cats, 'name', 'id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $product): RedirectResponse
    {
        $product = auth()->user()->products()->findOrFail($product);
        $data = $request->validated();
        $data['is_negotiable'] = YesNo::from($data['is_negotiable'])->toBool();

        DB::transaction(function () use ($product, $data, $request) {
            $product->update(Arr::except($data, ['featured', 'gallery', 'categories', 'removed_gallery_urls']));

            $product->categories()->sync($data['categories']);
            if ($request->hasFile('featured')) {
                $product->addMediaFromRequest('featured')->toMediaCollection('featured');
            }

            $removedUrls = $request->input('removed_gallery_urls', []);
            if (!empty($removedUrls)) {
                $galleryMedia = $product->getMedia('gallery');
                foreach ($galleryMedia as $media) {
                    if (in_array($media->getUrl(), $removedUrls)) {
                        $media->delete();
                    }
                }
            }
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $product->addMedia($file)->toMediaCollection('gallery');
                }
            }
        });

        return back()->with(successRes("Product updated successfully."));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('my-products.index')
            ->with(successRes("Product deleted successfully."));
    }
}
