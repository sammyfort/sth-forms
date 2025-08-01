<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Signboard;
use App\Models\SignboardCategory;
use App\Services\RatingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SignboardPublicController extends Controller
{
    public function index(): Response
    {
        $signboards = QueryBuilder::for(Signboard::class)
            ->allowedFilters([
                AllowedFilter::callback('q', function (Builder $query, $input) {
                    if (is_array($input)){
                        $input = implode(',', $input);
                    }
                    $query->where("name", "LIKE", "%$input%")
                        ->orWhere("town", "LIKE", "%$input%")
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
            // ->inRandomOrder()
            ->paginate(8)
            ->appends(request()->query());

        $signboards->map(function (Signboard $signboard) {
            $signboard->featured_url = $signboard->getFirstMediaUrl('featured');
            return $signboard;
        });

        return Inertia::render('Signboards/Signboards', [
            'signboardsData' => $signboards,
            'regions' => Region::query()->select(['id', 'name'])->get(),
            'categories' => SignboardCategory::query()->select(['id', 'name'])->get(),
        ]);
    }

    public function show(Signboard $signboard): Response
    {
        $signboard->loadMissing(['reviews.ratings', 'business.user', 'region', 'categories', 'media']);
        $averageRatings = $signboard->averageRatings();
        $distributions = RatingService::getDistributions($signboard);

        if (!auth() || auth()->id() != $signboard->created_by_id){
            $viewCooldown = now()->addHours(3);
            views($signboard)->cooldown($viewCooldown)->record();
        }
        $signboard->views_count = views($signboard)->count();

        return Inertia::render('Signboards/Signboard', [
            'signboard' => $signboard->toArrayWithMedia(),
            'ratings' => $averageRatings,
            'distributions' => $distributions,
        ]);
    }

    public function getPromotedSignboards(): JsonResponse
    {
        $signboards = Signboard::getRandomPromotedQuery()
            ->with(['business', 'region'])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            })->get();

        if ($signboards->count() < 1) {
            $signboards = Signboard::query()
                ->with(['business', 'region'])
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

        $signboards->map(function (Signboard $signboard) {
            $signboard->featured_url = $signboard->getFirstMediaUrl('featured');
        });

        return response()->success([
            'signboards' => $signboards
        ]);
    }
}
