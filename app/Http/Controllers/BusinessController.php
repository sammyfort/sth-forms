<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BusinessController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Businesses/Businesses');
    }

    public function myBusinesses(): Response
    {
        return Inertia::render('Businesses/MyBusinesses');
    }
}
