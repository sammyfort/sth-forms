<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Signboard;
use App\Models\SignboardCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SignboardController extends Controller
{
    public function index(): Response{
        $signboards = QueryBuilder::for(Signboard::class)
            ->allowedFilters([
                AllowedFilter::callback('q', function (Builder $query, $input){
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
            ->with(['business', 'region'])
            ->inRandomOrder()
            ->paginate(8);


        $regions = Region::query()->select(['id', 'name'])->get();
        $categories = SignboardCategory::query()->select(['id', 'name'])->get();

        return Inertia::render('Signboards/Signboards', [
            'signboardsData' => $signboards,
            'regions' =>  $regions,
            'categories' =>  $categories,
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
}
