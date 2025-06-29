<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Region;
use App\Models\Signboard;
use App\Models\SignboardCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class SignboardController extends Controller
{
    public function index(): Response{
        $signboards = Signboard::query()
            ->with(['business', 'region'])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
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
