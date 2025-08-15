<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class CountryBuilderService
{
    public static function applyRegionFilterAuth(Builder|QueryBuilder $builder, $relationName='region'): LengthAwarePaginator
    {
        $user = auth()->user();
        $builderCopy = clone $builder;

        if ($user && $user->country){
            $data = $builder
                ->whereRelation($relationName, 'country_id', $user->country->id)
                ->inRandomOrder()
                ->paginate(12)
                ->appends(request()->query());

            if ($data->count() < 1){
                $data = $builderCopy
                    ->inRandomOrder()
                    ->paginate(12)
                    ->appends(request()->query());
            }
        }
        else {
            $data = $builderCopy
                ->inRandomOrder()
                ->paginate(12)
                ->appends(request()->query());
        }
        return $data;
    }

    public static function applyRegionFilterAuthOnPromoted(Builder|QueryBuilder $builder, $relationName='region'): Collection
    {
        $user = auth()->user();
        $builderCopy = clone $builder;

        if ($user && $user->country){
            $data = $builder
                ->whereRelation($relationName, 'country_id', $user->country->id)
                ->inRandomOrder()
                ->take(10)
                ->get();

            if ($data->count() < 1){
                $data = $builderCopy
                    ->inRandomOrder()
                    ->take(10)
                    ->get();
            }
        }
        else {
            $data = $builderCopy
                ->inRandomOrder()
                ->take(10)
                ->get();
        }
        return $data;
    }
}
