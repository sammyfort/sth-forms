<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResearchStepRequest;
use App\Models\Category;
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
            })
        ]);
    }


    public function validateStep(ResearchStepRequest $request, int $step)
    {
        $request->validated();
        return back();
    }

}
