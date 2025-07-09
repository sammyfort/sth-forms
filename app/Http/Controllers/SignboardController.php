<?php

namespace App\Http\Controllers;


use App\Http\Requests\Signboard\RateRequest;
use App\Http\Requests\Signboard\StoreSignboardRequest;
use App\Http\Requests\Signboard\UpdateSignboardRequest;
use App\Models\Region;
use App\Models\Signboard;
use App\Models\SignboardCategory;
use App\Models\SignboardSubscriptionPlan;
use App\Models\User;
use App\Services\GhanaPostService;
use App\Services\HelperService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SignboardController extends Controller
{

    public function __construct(protected HelperService $helperService)
    {

    }

    public function index(): Response
    {
        $signboards = QueryBuilder::for(Signboard::class)
            ->allowedFilters([
                AllowedFilter::callback('q', function (Builder $query, $input) {
                    $query->where("town", "LIKE", "%$input%")
                        ->orWhere("street", "LIKE", "%$input%")
                        ->orWhere("landmark", "LIKE", "%$input%")
                        ->orWhere("blk_number", "LIKE", "%$input%")
                        ->orWhere("gps", "LIKE", "%$input%")
                        ->orWhereRelation('business', 'name', "LIKE", "%$input%");
                }),
                AllowedFilter::callback('categories', function ($query, $value) {
                    $ids = is_array($value) ? $value : explode(',', $value);
                    $query->whereHas('categories', function ($q) use ($ids) {
                        $q->whereIn('signboard_categories.id', $ids);
                    });
                }),
                AllowedFilter::exact('region', 'region_id'),
            ])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            })
            ->with(['business', 'region'])
//            ->inRandomOrder()
            ->paginate(8);

        $signboards->map(function (Signboard $signboard) {
            $signboard->featured_url = $signboard->getFirstMediaUrl('featured');
            return $signboard;
        });

        $regions = Region::query()->select(['id', 'name'])->get();
        $categories = SignboardCategory::query()->select(['id', 'name'])->get();

        return Inertia::render('Signboards/Signboards', [
            'signboardsData' => $signboards,
            'regions' => $regions,
            'categories' => $categories,
        ]);
    }

    public function show(Signboard $signboard): Response
    {
        $signboard->loadMissing(['reviews.ratings', 'business.user', 'region', 'categories', 'media']);
        $averageRatings = $signboard->averageRatings();

        // find ratings distributions
        $totalReviews = $signboard->totalReviews();
        $distributions = [
            5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0,
        ];

        $reviews = $signboard->reviews;
        $reviewers = User::query()->whereIn('id', $reviews->pluck('user_id')->toArray())->get(['id', 'firstname', 'lastname']);
        $reviews->map(function ($review) use (&$distributions, $reviewers) {
            $ar = $review->ratings->avg('value');
            $reviewRating = ratingFormat($ar, 0);
            $distributions[(int)$reviewRating] += 1;

            $review->user = $reviewers->where('id', $review->user_id)->first();
            $review->average_rating = ratingFormat($ar);
            $review->created_at_str = Carbon::parse($review->created_at)->diffForHumans();
        });

        foreach ($distributions as $key => $value) {
            if($totalReviews == 0){
                $distributions[$key] = 0;
            }
            else{
                $distributions[$key] = ((int)$value / (int)$totalReviews) * 100;
            }
        }
        $viewCooldown = now()->addHours(3);
        views($signboard)->cooldown($viewCooldown)->record();
        $signboard->views_count = views($signboard)->count();
        return Inertia::render('Signboards/Signboard', [
            'signboard' => $signboard->toArrayWithMedia(),
            'ratings' => $averageRatings,
            'distributions' => $distributions,
        ]);
    }

    public function getPromotedSignboards(): JsonResponse
    {
        $signboards = Signboard::query()
            ->with(['business', 'region'])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            })
            ->whereHas('subscriptions', function ($subscriptionQuery) {
                $subscriptionQuery->where('ends_at', '>=', now());
            })
            ->inRandomOrder()
            ->take(10)
            ->get();
        $signboards->map(function (Signboard $signboard) {
            $signboard->featured_url = $signboard->getFirstMediaUrl('featured');
        });
        return response()->success([
            'signboards' => $signboards
        ]);
    }

    public function rate(Signboard $signboard, RateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
        try {
            $reviewData = [
                'review' => $validatedData['review'],
                // 'recommend'  => true,
                'ratings' => [
                    'overall' => $validatedData['overall'],
                    'customer_service' => $validatedData['customer_service'],
                    'quality' => $validatedData['quality'],
                    'price' => $validatedData['price'],
                    'communication' => $validatedData['communication'],
                    'speed' => $validatedData['speed'],
                ],
            ];
            // check if user has already rated
            $review = $signboard->reviews()->where('user_id', auth()->id())->first();
            if ($review) {
                $signboard->updateReview($review->id, $reviewData);
            } else {
                $review = $signboard->addReview($reviewData, auth()->id());
            }
            DB::commit();
            $signboard->refresh();
            $signboard->loadMissing([
                'business',
                'region',
                'reviews' => function ($reviewsQuery) {
                    $reviewsQuery->where('user_id', auth()->id())
                        ->with(['ratings']);
                },
            ]);
            $data['signboard'] = $signboard;
            return back()->with(successRes('Signboard Rated Successfully', $data));
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->with(errorRes());
        }
    }

    public function mySignboards(): Response
    {
        $user = auth()->user();
        return Inertia::render('Signboards/MySignboards', [
            'signboards' => $user->signboards()->with(['region', 'business'])->latest()->paginate(10),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Signboards/SignboardCreate',[
            'business' => $request->business,
            'categories' => $this->helperService->getCategories(),
            'regions' => $this->helperService->getRegions(),
            'businesses' => $this->helperService->getAuthBusinesses()
        ]);
    }

    public function store(StoreSignboardRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $business = $request->user()->businesses()->findOrFail($data['business_id']);

        DB::transaction(function () use ($business, $data, $request) {
            $signboard = $business->signboards()->create(Arr::except($data, ['featured_image', 'gallery_images', 'categories']));
            $signboard->categories()->sync($data['categories']);

            if ($request->hasFile('featured_image')) {
                $signboard->addMediaFromRequest('featured_image')->toMediaCollection('featured');
            }

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $signboard->addMedia($file)->toMediaCollection('gallery');
                }
            }

        });

        return back()->with(successRes("Signboard created successfully."));
    }


    public function showMySignboard(Signboard $signboard): Response
    {
        Gate::authorize('view', [$signboard, request()->user()]);
        $subPlans = SignboardSubscriptionPlan::query()->get(['id', 'name', 'description', 'number_of_days', 'price']);

        return Inertia::render('Signboards/SignboardShow', [
            'signboard' => $signboard->load(['business', 'region', 'reviews', 'categories'])->toArrayWithMedia(),
            'payment_status' => request()->get('payment_status'),
            'plans' => $subPlans,
        ]);
    }

    public function edit(Signboard $signboard): Response
    {
        Gate::authorize('update', [$signboard, request()->user()]);

        return Inertia::render('Signboards/SignboardEdit', [
            'signboard' => $signboard->load(['business', 'region', 'categories'])->toArrayWithMedia(),
            'categories' => $this->helperService->getCategories(),
            'regions' => $this->helperService->getRegions(),
            'businesses' => $this->helperService->getAuthBusinesses()
        ]);
    }


    public function update(UpdateSignboardRequest $request, Signboard $signboard): RedirectResponse
    {
        Gate::authorize('update', [$signboard, request()->user()]);

        $data = $request->validated();

        //dd($data);
        DB::transaction(function () use ($signboard, $data, $request) {
            $signboard->update(Arr::except($data, ['featured_image', 'gallery_images', 'removed_gallery_urls', 'categories']));
            $signboard->categories()->sync($data['categories']);

            if ($request->hasFile('featured_image')) {
                $signboard->addMediaFromRequest('featured_image')->toMediaCollection('featured');
            }

            $removedUrls = $request->input('removed_gallery_urls', []);
            if (!empty($removedUrls)) {

                $galleryMedia = $signboard->getMedia('gallery');
                foreach ($galleryMedia as $media) {
                    if (in_array($media->getUrl(), $removedUrls)) {
                        $media->delete();
                    }
                }
            }
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $signboard->addMedia($file)->toMediaCollection('gallery');
                }
            }

        });

        return back()->with(successRes("Signboard updated successfully."));
    }

    public function delete(Signboard $signboard): RedirectResponse
    {
        Gate::authorize('delete', [$signboard, request()->user()]);
        $signboard->delete();
        return redirect()->route('my-signboards.index')
            ->with(successRes("Signboard deleted successfully."));
    }

}
