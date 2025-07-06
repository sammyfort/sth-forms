<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignboarbSubscriptionRequest extends FormRequest
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
            'signboard_id' => ['required', 'exists:signboards,id'],
            'plan_id' => ['required', 'exists:signboard_subscription_plans,id'],
        ];
    }
}
