<?php

namespace App\Http\Controllers;

use App\Models\Signboard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
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
        return Inertia::render('Signboards/Signboards', [
            'signboards' => $signboards
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
}
