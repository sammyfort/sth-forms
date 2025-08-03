<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\PromotionPlan;
use App\Models\Region;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function getMyServices(): Response
    {
        $sort = request('sort', 'desc');

        return Inertia::render('Services/MyServices',[
            'services' =>  auth()->user()->services()
                ->with(['region', 'category', 'user',])
                ->orderBy('created_at', $sort)
                ->paginate(12)
                ->withQueryString(),
            'sort' => $sort,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Services/ServiceCreate',[
            'categories' => ServiceCategory::select(['id', 'name'])
                ->get()
                ->map(fn($cat) => [
                    'label' => $cat->name,
                    'value' => $cat->id,
                ]),
             'regions' => Region::select(['id', 'name'])->get()
                 ->map(fn($reg) => ['label' => $reg->name, 'value' => $reg->id,])
        ]);
    }

    public function showMyService(Service $service): Response
    {
        $service->views_count = views($service)->count();
        $plans = PromotionPlan::query()->get(['id', 'name', 'description', 'number_of_days', 'price']);
        $service = $service->loadMissing(['user','category', 'region', 'promotions.plan']);

        // check if it has payment
        $paymentStatus = Promotion::routeCallback();

        return Inertia::render('Services/ServiceShow',[
            'service' => $service,
            'plans' => $plans,
            'payment_status' => $paymentStatus,
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request) {
            $service = auth()->user()->services()->create(
                Arr::except($data, ['featured', 'gallery'])
            );

            $service->handleUploads($request, [
                'featured' => 'featured',
                'gallery' => 'gallery',
            ]);
        });

        return back()->with(successRes("Service created successfully."));
    }

    public function edit(Service $service): Response
    {
        return Inertia::render('Services/ServiceEdit',[
            'service' =>  $service->load(['user', 'region'])->toArrayWithMedia(),
            'categories' => ServiceCategory::select(['id', 'name'])
                ->get()
                ->map(fn($cat) => [
                    'label' => $cat->name,
                    'value' => $cat->id,
                ]),
            'regions' => Region::select(['id', 'name'])->get()
                ->map(fn($reg) => ['label' => $reg->name, 'value' => $reg->id,])
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($service, $data, $request) {
            $service->update(Arr::except($data, ['featured', 'gallery', 'removed_gallery_urls']));


            if ($request->hasFile('featured')) {
                $service->addMediaFromRequest('featured')->toMediaCollection('featured');
            }

            $removedUrls = $request->input('removed_gallery_urls', []);
            if (!empty($removedUrls)) {

                $galleryMedia = $service->getMedia('gallery');
                foreach ($galleryMedia as $media) {
                    if (in_array($media->getUrl(), $removedUrls)) {
                        $media->delete();
                    }
                }
            }
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $service->addMedia($file)->toMediaCollection('gallery');
                }
            }
        });

        return back()->with(successRes("Service updated successfully."));

    }

    public function destroy(Service $service): RedirectResponse
    {

        $service->delete();
        return redirect()->route('my-services.index')
            ->with(successRes("Service deleted successfully."));
    }
}
