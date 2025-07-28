<?php

namespace App\Http\Requests\Service;

use App\Rules\GPSRule;
use App\Rules\MobileNumber;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['required'],
            'first_mobile' => ['required', new MobileNumber()],
            'second_mobile' => ['nullable', new MobileNumber()],
            'business_name' => ['nullable'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable'],
            'region_id' => ['required', 'exists:regions,id'],
            'town' => ['required'],
            'gps' => ['nullable', new GPSRule()],
            'category_id' => ['required'],
            'featured' => ['required', 'image', 'max:2048'],
            'gallery' => ['required', 'array'],
            'gallery.*' => ['image', 'max:2048'],
        ];
    }
}
