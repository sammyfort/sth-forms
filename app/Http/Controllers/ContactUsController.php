<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUsMessage;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function store(ContactUsRequest $request):  \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validated();
        ContactUsMessage::query()->create($validatedData);
        return back()->with(successRes("Thanks for contacting us. We will get back to you soon."));
    }
}
