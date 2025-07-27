<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home');
    }

    public function searchDirectory(Request $request): \Illuminate\Http\RedirectResponse
    {
        $query = $request->input('q');

        switch ($request->input('directory')){
            case 'services':
                return redirect(route('services.index')."?filter[q]=".$query);
            case 'signboards':
                return redirect(route('signboards.index')."?filter[q]=".$query);
            case 'jobs':
                return redirect(route('jobs.index')."?filter[q]=".$query);
            case 'shops':
                return redirect(route('shops.index')."?filter[q]=".$query);
            default:
                return back();
        }
    }
}
