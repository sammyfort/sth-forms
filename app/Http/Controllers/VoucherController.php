<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessVoucherPayment;
use App\Models\Category;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Voucher/VoucherIndex', [

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * @throws Throwable
     */


    public function paystackLiveWebhook(Request $request): ?JsonResponse
    {

        if ($this->isFromPaystack($request)){
            $payload = json_decode($request->getContent(), true);
            ProcessVoucherPayment::dispatch($payload);

            http_response_code(200);
            return response()->json(['status' => 'success'], 200);
        }

        return null;
    }

    public function isFromPaystack($request): bool
    {
        if(!$request->hasHeader("X-Paystack-Signature")){
            return false;
        }
        $computed = hash_hmac('sha512', $request->getContent(),  config("velstack.PAYSTACK_LIVE_SECRET_KEY"));

        if ($request->header("X-Paystack-Signature") !== $computed){
            return false;
        }
        return true;
    }

}
