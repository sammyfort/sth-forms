<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Region;
use App\Models\Signboard;
use App\Models\SignboardCategory;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
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


    public function mySignboards(): Response
    {
        $user = auth()->user();
        return Inertia::render('Signboards/MySignboards', [
           'signboards' => $user->signboards()->with(['region', 'business'])->latest()->get(),
            'businesses' => $user->businesses()
                ->select('id', 'name')
                ->get()
                ->map(fn($business) => [
                    'label' => $business->name,
                    'value' => (string) $business->id,
                ]),
        ]);
    }


    public function create(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $business = $request->user()->businesses()->find($data['business_id']);
        $business->signboards()->create($data);
        return back()->with(successRes("Signboard created successfully."));
    }
    public function show(Signboard $signboard): Response
    {
        return Inertia::render('Signboards/SignboardShow', [
            'signboard' => $signboard->load(['business', 'region']),
        ]);
    }


    public function update(Request $request, Signboard $signboard): RedirectResponse
    {
        Gate::authorize('update', [$signboard, request()->user()]);
        $data = $request->validate($this->rules());
        $signboard->update($data);
        return back()->with(successRes("Signboard updated successfully."));
    }


    public function delete(Signboard $signboard): RedirectResponse
    {
        Gate::authorize('delete', [$signboard, request()->user()]);
        $signboard->delete();
        return redirect()->route('my-signboards.index')
            ->with(successRes("Signboard deleted successfully."));
    }

    public function rules(): array
    {
        return [
            'business_id' => ['required', Rule::exists('businesses', 'id')->where('user_id', request()->user()->id)],
            'region_id' => ['required', Rule::exists('regions', 'id')],
            'town' => ['required', 'string'],
            'street' => ['nullable', 'string'],
            'landmark' => ['required', 'string'],
            'blk_number' => ['nullable', 'string'],
            'gps' => ['required', 'string'],

        ];
    }


}
