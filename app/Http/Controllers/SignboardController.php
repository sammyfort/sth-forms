<?php

namespace App\Http\Controllers;

use App\Http\Requests\Signboard\RateRequest;
use App\Models\Region;
use App\Models\Signboard;
use App\Models\SignboardCategory;
use Codebyray\ReviewRateable\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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

    public function promoted(): JsonResponse
    {
        $signboards = Signboard::query()
            ->with(['business', 'region'])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->inRandomOrder()
            ->take(10)
            ->get();
        return $this->apiSuccessResponse([
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
}
