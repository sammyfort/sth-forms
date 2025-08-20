<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResearchStepRequest extends FormRequest
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
        $step = $this->route('step');

        return match ($step) {
            'principal_investigator' => [
                'voucher_code' => 'required|string|exists:vouchers,code',
                'category_id'  => 'required|exists:categories,id',
            ],

            'study_coordinator' => [
                'letter' => 'required|file|mimes:pdf,docx',
            ],
            'research_work' => [
                // other rules
            ],

            default => [],
        };
    }
}
