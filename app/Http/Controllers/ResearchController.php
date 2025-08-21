<?php

namespace App\Http\Controllers;

use App\Enums\InstitutionInvestigator;
use App\Enums\StaffCategory;
use App\Enums\Title;
use App\Enums\YesNo;
use App\Http\Requests\ResearchStepRequest;
use App\Models\Category;
use App\Models\ResearchApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResearchController extends Controller
{
    public function index()
    {
        return Inertia::render('Research/ResearchApply', [
            'categories' => Category::all()->map(function ($category) {
                return [
                    'id' => $category->id,
                    'value' => $category->id,
                    'label' => $category->name,
                    'price' => $category->price,
                ];
            }),

             'titles' => toLabelValue(Title::toArray()),
            'institution_investigators' => toLabelValue(InstitutionInvestigator::toArray()),
            'staff_categories' => toLabelValue(StaffCategory::toArray()),
            'yesno' => toLabelValue(YesNo::toArray()),

        ]);
    }


    public function validateStep(ResearchStepRequest $request, string $step)
    {
        $request->validated();
        return back();
    }

    public function submit (ResearchStepRequest $request)
    {
        $validated = $request->validate(ResearchStepRequest::allRules());

        $submission = ResearchApplication::query()->create($validated);
        return to_route('home');


    }

}
