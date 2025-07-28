<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Enums\JobType;
use App\Http\Requests\Job\StoreJobRequest;
use App\Http\Requests\Job\UpdateJobRequest;
use App\Models\Region;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
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


    public function show(Job $job): Response
    {
        return Inertia::render('Jobs/JobShow');
    }


    public function create(): Response
    {
        return Inertia::render('Jobs/JobCreate', [
            'statuses' => collect(JobStatus::toArray())->map(fn($type) => [
                'label' => str($type)->headline(),
                'value' => $type,
            ])->values(),
            'types' => collect(JobType::toArray())->map(fn($type) => [
                'label' => str($type)->headline(),
                'value' => $type,
            ])->values()
        ]);
    }


    public function store(StoreJobRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request) {
            $job = $request->user()->jobs()->create(Arr::except($data, ['featured', 'gallery']));
            $job->handleUploads($request, [
                'featured' => 'featured',
                'gallery' => 'gallery',
            ]);
        });

        return back()->with(successRes("Job created successfully."));
    }

    public function edit(Job $job): Response
    {
        return Inertia::render('Jobs/JobEdit');
    }

    public function update(UpdateJobRequest $request, Job $job): RedirectResponse
    {
        $data = $request->validated();

        return back()->with(successRes("Job updated successfully."));

    }

    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();
        return redirect()->route('my-jobs.index')
            ->with(successRes("Job deleted successfully."));
    }
}
