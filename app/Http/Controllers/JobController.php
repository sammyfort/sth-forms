<?php

namespace App\Http\Controllers;

use App\Enums\JobMode;
use App\Enums\JobStatus;
use App\Enums\JobType;
use App\Http\Requests\Job\StoreJobRequest;
use App\Http\Requests\Job\UpdateJobRequest;
use App\Models\JobCategory;
use App\Models\PromotionPlan;
use App\Models\Region;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class JobController extends Controller
{
    public function getMyJobs(): Response
    {
        $sort = request('sort', 'desc');
        $status = request('status', 'all');
        $search = request('search');

        $query = auth()->user()->jobs()->orderBy('created_at', $sort);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        return Inertia::render('Jobs/MyJobs', [
            'jobs' => $query->paginate(12)->withQueryString(),
            'sort' => $sort,
            'status' => $status,
            'search' => $search,
        ]);
    }


    public function showMyJob(\App\Models\Job $job): Response
    {
        //  $job->views_count = views($job)->count();
        $job->loadMissing(['region', 'categories', 'promotions']);

        //dd($job->toArray());
        return Inertia::render('Jobs/JobShow', [
            'job' => $job,
            'plans' => PromotionPlan::query()->get(['id', 'name', 'description', 'number_of_days', 'price'])
        ]);
    }


    public function create(): Response
    {
        $regions = Region::select('id', 'name')->get();
        $categories = JobCategory::select('id', 'name')->get();
        return Inertia::render('Jobs/JobCreate', [
            'categories' => toLabelValue($categories, 'name', 'id'),
            'statuses' => toLabelValue(JobStatus::toArray()),
            'types' => toLabelValue(JobType::toArray()),
            'modes' => toLabelValue(JobMode::toArray()),
            'regions' => toLabelValue($regions, 'name', 'id'),
        ]);
    }


    public function store(StoreJobRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request) {
            $job = $request->user()->jobs()->create(Arr::except($data, ['featured', 'categories']));

            $job->handleUploads($request, [
                'featured' => 'featured',
            ]);

            $job->categories()->sync($data['categories']);
        });

        return back()->with(successRes("Job created successfully."));
    }


    public function edit(Job $job): Response
    {
        $regions = Region::select('id', 'name')->get();
        $categories = JobCategory::select('id', 'name')->get();

        return Inertia::render('Jobs/JobEdit', [
            'job' => $job->loadMissing(['categories'])->toArrayWithMedia(),
            'categories' => toLabelValue($categories, 'name', 'id'),
            'statuses' => toLabelValue(JobStatus::toArray()),
            'types' => toLabelValue(JobType::toArray()),
            'modes' => toLabelValue(JobMode::toArray()),
            'regions' => toLabelValue($regions, 'name', 'id'),
        ]);
    }

    public function update(UpdateJobRequest $request, Job $job): RedirectResponse
    {
        $data = $request->validated();

        //dd($data);
        DB::transaction(function () use ($request, $data, $job) {
            $job->refresh();
            $job->update(Arr::except($data, ['featured', 'categories']));

            $job->categories()->sync($data['categories']);

            $job->handleUploads($request, [
                'featured' => 'featured',
            ]);
        });

        return back()->with(successRes("Job updated successfully."));
    }


    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();
        return redirect()->route('my-jobs.index')
            ->with(successRes("Job deleted successfully."));
    }
}
