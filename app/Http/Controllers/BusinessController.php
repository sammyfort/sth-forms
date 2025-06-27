<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function publicIndex(): Response
    {
        return Inertia::render('Businesses/Businesses');
    }

}
