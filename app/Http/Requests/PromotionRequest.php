<?php

namespace App\Http\Requests;

use App\Enums\Promotable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'promotable_id' => ['required', 'integer'],
            'promotable_type' => ['required', Rule::enum(Promotable::class)],
            'plan_id' => ['required', 'exists:promotion_plans,id'],
        ];
    }
}
