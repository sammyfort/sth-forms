<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Signboard;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        $promotions = Signboard::getAllPromotionsQuery()
            ->where('created_by_id', $user->id)
            ->get();
        $signboards = $user->signboards()->with(['business'])->get();
        return Inertia::render('Dashboard/Dashboard', [
            'promotions' => $promotions,
            'signboards' => $signboards,
        ]);
    }
}
