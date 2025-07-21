<?php

namespace App\Http\Requests;

use App\Enums\Promotable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromotionRequest extends FormRequest
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
            'promotable_id' => ['required', 'integer'],
            'promotable_type' => ['required', Rule::enum(Promotable::class)],
            'plan_id' => ['required', 'exists:promotion_plans,id'],
        ];
    }
}
