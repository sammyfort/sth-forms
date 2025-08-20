<?php

namespace App\Http\Requests;

use App\Enums\InstitutionInvestigator;
use App\Enums\StaffCategory;
use App\Enums\Title;
use App\Enums\YesNo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
               'title' => ['required', Rule::in(Title::toArray())],
                'surname' => ['required', 'string'],
                'other_name' => ['required', 'string'],
                'email' => ['required', 'email'],
                'telephone' => ['required', 'string'],
                'postal_address' => ['required', 'string'],
                'institution_investigator' => ['required', Rule::in(InstitutionInvestigator::toArray())],
                'department_unit' => ['required', 'string'],
                'directorate' => ['required', 'string'],
            ],

            'study_coordinator' => [
                'study_coordinator_name' => ['required', 'string'],
                'coordinator_email' => ['required', 'email'],
                'local_coordinator_address' => ['required', 'string'],
                'staff_categories' => ['required', 'array', Rule::in(StaffCategory::toArray())],
            ],
            'research_work' => [
                'research_category' => ['required', 'string'],
                'observation_study' => ['required', 'string'],
                'interventional_study' => ['required', 'string'],
                'study_location_level' => ['required', 'string'],
                'documents' => ['required', 'array'],
            ],

            'research_details' => [
                'study_location_in_sth' => ['required', 'string'],
                'study_title' => ['required', 'string'],
                'study_design' => ['required', 'array'],
                'study_objectives' => ['required', 'string'],
            ],

            'inclusion_and_exclusion_criteria' => [
                'inclusion_criteria' => ['required', 'string'],
                'exclusion_criteria' => ['required', 'string'],
                'sample_size' => ['required', 'numeric'],
            ],

            'study_participation_duration' => [
                'proposed_start_date' => ['required', 'date'],
                'proposed_end_date' => ['required', 'date'],
                'proposed_study_duration' => ['required', 'string'],
                'recruitment_start_date' => ['required', 'date'],
                'recruitment_end_date' => ['required', 'date'],
                'source_of_fund'=> ['required', 'string'],
                'research_fund_base' => ['required', 'string'],
                'research_total_budget' => ['required', 'string'],
                'study_support_from_sth' => ['required', 'string'],
                'equipments_needed' => ['required', 'string'],
            ],
            'communication_publication' => [
                'organize_forum' => ['required', Rule::in(YesNo::toArray())],
                'post_on_sth_website' => ['required', Rule::in(YesNo::toArray())],
                'agreement' => ['required', 'boolean'],

            ],

            default => [],
        };
    }
}
