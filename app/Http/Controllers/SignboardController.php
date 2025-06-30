<?php

namespace App\Http\Controllers;


use App\Http\Requests\Signboard\RateRequest;
use App\Models\Region;
use App\Models\Signboard;
use App\Models\SignboardCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
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


        $regions = Region::query()->select(['id', 'name'])->get();
        $categories = SignboardCategory::query()->select(['id', 'name'])->get();

        return Inertia::render('Signboards/Signboards', [
            'signboardsData' => $signboards,
            'regions' => $regions,
            'categories' => $categories,
        ]);
    }

    public function getPromotedSignboards(): JsonResponse
    {
        $signboards = Signboard::query()
            ->with(['business', 'region'])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->inRandomOrder()
            ->take(10)
            ->get();
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
//                'recommend'  => true,      // Whether the user would recommend the product being reviewed
                'approved' => true,      // Optionally override default (false) approval by providing 'approved'
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
            }
            else{
                $signboard->addReview([$reviewData], auth()->id());
            }
            DB::commit();
            return back()->with(successRes('Signboard Rated Successfully'));
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
    public function create(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $business = $request->user()->businesses()->find($data['business_id']);
        $business->signboards()->create($data);
        return back()->with(successRes("Signboard created successfully."));
    }
    public function show(Signboard $signboard): Response
    {
        return Inertia::render('Signboards/SignboardShow', [
            'signboard' => $signboard->load(['business', 'region']),
        ]);
    }


    public function update(Request $request, Signboard $signboard): RedirectResponse
    {
        Gate::authorize('update', [$signboard, request()->user()]);
        $data = $request->validate($this->rules());
        $signboard->update($data);
        return back()->with(successRes("Signboard updated successfully."));
    }


    public function delete(Signboard $signboard): RedirectResponse
    {
        Gate::authorize('delete', [$signboard, request()->user()]);
        $signboard->delete();
        return redirect()->route('my-signboards.index')
            ->with(successRes("Signboard deleted successfully."));
    }

    public function rules(): array
    {
        return [
            'business_id' => ['required', Rule::exists('businesses', 'id')->where('user_id', request()->user()->id)],
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'town' => ['required', 'string'],
            'street' => ['nullable', 'string'],
            'landmark' => ['required', 'string'],
            'blk_number' => ['nullable', 'string'],
            'gps' => ['required', 'string'],

        ];
    }
}
