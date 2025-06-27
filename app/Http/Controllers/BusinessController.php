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
        return Inertia::render('Businesses/Businesses');
    }

    public function create(Request $request): RedirectResponse
    {

      $data =  $request->validate([
           'name' => ['required'],
            'email' => ['required', 'email'],
            'mobile' => ['required', ],
            'facebook' => ['required'],
            'instagram' => ['required'],
            'x' => ['required'],
            'linkedin' => ['required'],
            'description' => ['required'],
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

        $data =  $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'mobile' => ['required', ],
            'facebook' => ['required'],
            'instagram' => ['required'],
            'x' => ['required'],
            'linkedin' => ['required'],
            'description' => ['required'],
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
