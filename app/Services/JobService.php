<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;


class JobService
{
    public static function getJobs(): LengthAwarePaginator
    {
        $jobsQuery = QueryBuilder::for(Job::class)
            ->allowedFilters([
                AllowedFilter::callback('q', function (Builder $query, $input) {
                    if (is_array($input)){
                        $input = implode(',', $input);
                    }
                    $query->where("title", "LIKE", "%$input%")
                        ->orWhere("company_name", "LIKE", "%$input%")
                        ->orWhere("town", "LIKE", "%$input%")
                        ->orWhere("salary", "LIKE", "%$input%");
                }),
                AllowedFilter::callback('work_mode', function ($query, $value) {
                    $workModes = is_array($value) ? $value : explode(',', $value);
                    $query->whereIn('work_mode', $workModes);
                }),
                AllowedFilter::callback('job_type', function ($query, $value) {
                    $jobTypes = is_array($value) ? $value : explode(',', $value);
                    $query->whereIn('job_type', $jobTypes);
                }),
                AllowedFilter::callback('categories', function ($query, $value) {
                    $ids = is_array($value) ? $value : explode(',', $value);
                    $query->whereHas('categories', function ($q) use ($ids) {
                        $q->whereIn('job_categories.id', $ids);
                    });
                }),
                AllowedFilter::exact('country', 'region.country_id'),
                AllowedFilter::exact('region', 'region_id'),
            ])
            ->when(auth()->user(), function ($q){
                $q->where('user_id', '!=', auth()->id());
            })
            ->with(['categories', 'region.country'])
            ->with(['media' => function ($mediaQuery) {
                $mediaQuery->where('collection_name', 'company_logo');
            }])
            ->where('status', JobStatus::ACTIVE);

        $jobs = CountryBuilderService::applyRegionFilterAuth($jobsQuery);

        return $jobs;
    }

    public static function getPromoted(): Collection
    {
        $jobs = Job::query()
            ->with(['user', 'region.country', 'categories'])
            ->with(['media' => function ($mediaQuery) {
                $mediaQuery->where('collection_name', 'company_logo');
            }])
            ->where('status', JobStatus::ACTIVE)
            ->latest()
            ->take(10)
            ->get();

        return $jobs;
    }
}
