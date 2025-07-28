<?php

namespace App\Http\Requests\Job;

use App\Enums\{JobMode, JobStatus, JobType};

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
            'company_name' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'job_type' => ['required', Rule::in(JobType::toArray())],
            'work_mode' => ['required', Rule::in(JobMode::toArray())],
            'status' => ['required', Rule::in(JobStatus::toArray())],
            'categories' => ['required', 'array'],
            'summary' => ['nullable', 'string'],
            'description' => ['required'],

            'region_id' => ['required'],
            'town' => ['required'],
            'salary' => ['nullable'],
            'how_to_apply' => ['nullable'],
            'application_link' => ['required'],

            'deadline' => ['required', 'date', 'after:today'],
            'featured' => ['required','image', 'max:2048']

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
