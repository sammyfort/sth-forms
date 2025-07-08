<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $subscriptions = request()->user()->subscriptions()->with(['signboard', 'signboard.business'])->get();
        $signboards = request()->user()->signboards()->with(['business'])->get();
       // $running =  request()->user()->subscriptions()->running()->get();
        return Inertia::render('Dashboard/Dashboard', [
            'subscriptions' => $subscriptions,
            'signboards' => $signboards,
        ]);
    }
}
