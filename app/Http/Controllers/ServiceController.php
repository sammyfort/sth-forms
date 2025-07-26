<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
            ->where('user_id', '!=', auth()->id())
            ->with('media', function ($builder){
                $builder->where('collection_name', 'featured');
            })
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

    public function getPromotedSignboards(): JsonResponse
    {
        $services = Service::getRandomPromotedQuery()
            ->with([['user', 'region', 'category']])
            ->get();

        if ($services->count() < 1){
            $services = Service::query()
                ->with(['user', 'region', 'category'])
                ->when(auth()->user(), function ($q){
                    $q->where('created_by_id', '!=', auth()->id());
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


    public function show(Service $service): Response
    {
        $service->loadMissing(['user', 'category', 'region'])->loadCount('views');

        if (!auth() || auth()->id() != $service->user_id){
            $viewCooldown = now()->addHours(3);
            views($service)->cooldown($viewCooldown)->record();
        }
        $service->views_count = views($service)->count();

        return Inertia::render('Services/Service', [
            'service' => $service
        ]);
    }

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

    public function showMyServices(Service $service): Response
    {
        return Inertia::render('Services/ServiceShow',[
            'service' => $service->loadMissing(['user','category', 'region'])
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request) {
            $categoryIds = collect($data['categories'])->map(function ($name) {
                $normalized = Str::lower(trim($name));
                return ServiceCategory::firstOrCreate(
                    ['name' => $normalized],
                    ['name' => $normalized]
                )->id;
            });

            $service = auth()->user()->services()->create(
                Arr::except($data, ['featured', 'gallery', 'categories'])
            );
            $service->categories()->sync(
                collect($categoryIds)->mapWithKeys(fn ($id) => [$id => ['uuid' => Str::uuid()]])->toArray()
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
            $service->update(Arr::except($data, ['featured', 'gallery', 'removed_gallery_urls', 'categories']));

            $categoryIds = collect($data['categories'])->map(function ($name) {
                $normalized = Str::lower(trim($name));
                return ServiceCategory::firstOrCreate(['name' => $normalized])->id;
            });

            $syncPayload = [];
            foreach ($categoryIds as $id) {
                $syncPayload[$id] = ['uuid' => Str::uuid()];
            }
            $service->categories()->sync($syncPayload);


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
