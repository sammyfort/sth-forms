<?php

namespace App\Http\Requests\Signboard;

use App\Models\Signboard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'overall' => ['required', 'numeric', 'min:1', 'max:5'],
            'customer_service' => ['required', 'numeric', 'min:1',  'max:5'],
            'quality' => ['required', 'numeric', 'min:1', 'max:5'],
            'price' => ['required', 'numeric', 'min:1', 'max:5'],
            'communication' => ['required', 'numeric', 'min:1', 'max:5'],
            'speed' => ['required', 'numeric', 'min:1', 'max:5'],
            'review' => ['required', 'string', 'max:400'],
        ];
    }

    public function messages(): array{
        return [
            'overall.min' => 'the :attribute rating must be filled',
            'customer_service.min' => 'the :attribute rating must be filled',
            'quality.min' => 'the :attribute rating must be filled',
            'price.min' => 'the :attribute rating must be filled',
            'communication.min' => 'the :attribute rating must be filled',
            'speed.min' => 'the :attribute rating must be filled',
        ];
    }
}
