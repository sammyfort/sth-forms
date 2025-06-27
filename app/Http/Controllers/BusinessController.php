<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class BusinessController extends Controller
{

    public function index(): Response
    {
        $businesses = request()->user()->businesses()->latest()->get();
        return Inertia::render('Businesses/MyBusinesses', [
            'businesses' => $businesses,
        ]);
    }

    public function publicIndex(): Response
    {
        $businesses = Business::all();
        return Inertia::render('Businesses/Businesses', [
            'businesses' => $businesses,
        ]);
    }

    public function create(Request $request): RedirectResponse
    {

        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'digits:10'],
            'description' => ['required'],
            'facebook' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'x' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
        ]);

        $request->user()->businesses()->create($data);
        return back()->with(successRes("Business created successfully."));

    }

    public function show(Business $business): Response
    {
        Gate::authorize('view', [$business, request()->user()]);

        return inertia('Businesses/MyBusinessShow', [
            'business' => $business,
        ]);
    }

    public function update(Request $request, Business $business): RedirectResponse
    {
        Gate::authorize('update', [$business, request()->user()]);

        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'digits:10'],
            'description' => ['required'],
            'facebook' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'x' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
        ]);

        $business->update($data);
        return back()->with(successRes("Business updated successfully."));
    }

    public function delete(Business $business): RedirectResponse
    {
        Gate::authorize('delete', [$business, request()->user()]);

        $business->delete();
        return redirect()->route('my-businesses.index')
            ->with(successRes("Business deleted successfully."));
    }


}
