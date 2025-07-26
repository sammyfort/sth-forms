<?php

namespace App\Http\Requests\Job;

use App\Enums\{JobStatus, JobType};

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;

class StoreJobRequest extends FormRequest
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

            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(JobType::toArray())],
            'status' => ['required', Rule::in(JobStatus::toArray())],
            'summary' => ['nullable', 'string'],
            'description' => ['required'],
            'contact_name' => ['required', 'string'],
            'contact_phone' => ['required', 'digits:10'],
            'contact_email' => ['nullable', 'string'],
            'contact_website' => ['nullable', 'string'],
            'expires_at' => ['required', 'date', 'after:today'],

        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->has('description')) {
            $this->merge([
                'description' => Purifier::clean($this->input('description')),
            ]);
        }
    }
}
