<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class JobPublicController extends Controller
{
    public function index(): Response
    {
        $jobs = QueryBuilder::for(Job::class)

            ->allowedFilters([
                AllowedFilter::callback('q', function ($query, $input) {
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
            ->where('status', JobStatus::ACTIVE)
            ->paginate(12)
            ->appends(request()->query());

        return Inertia::render('Jobs/Jobs', [
            'jobsData' => $jobs,
            'categories' => JobCategory::query()->get(),
            'regions' => Region::query()->get(),
        ]);
    }

    public function show(Job $job): Response
    {
        $job->loadMissing(['categories', 'region.country']);
        $relatedJobs = Job::query()
            ->whereRelation('categories', function ($catQuery) use ($job){
                $catQuery->whereIn('category_id', $job->categories->pluck('id')->toArray());
            })
            ->with(['categories', 'region.country'])
            ->with(['media' => function ($mediaQuery) {
                $mediaQuery->where('collection_name', 'company_logo');
            }])
            ->take(6)
            ->inRandomOrder()
            ->get();

        return Inertia::render('Jobs/Job', [
            'job' => $job,
            'relatedJobs' => $relatedJobs,
        ]);
    }

    public function getPromotedJobs(): JsonResponse
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

        return response()->success([
            'jobs' => $jobs
        ]);
    }
}
