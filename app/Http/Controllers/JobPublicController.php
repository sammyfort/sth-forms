<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Region;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;


class JobPublicController extends Controller
{
    public function index(): Response
    {
        $jobs = JobService::getJobs();
        $regions = Region::query()->get(['id', 'name']);
        $categories = JobCategory::query()->get();

        return Inertia::render('Jobs/Jobs', [
            'jobsData' => $jobs,
            'categories' => $categories,
            'regions' => $regions,
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
        $jobs = JobService::getPromoted();

        return response()->success([
            'jobs' => $jobs
        ]);
    }
}
