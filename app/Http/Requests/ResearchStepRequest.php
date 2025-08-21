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
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $step = $this->route('step');

        return match ($step) {
            'voucher_validation' => $this->voucherCodeAndCategory(),
            'principal_investigator' => $this->principalInvestigatorRules(),
            'study_coordinator' => $this->studyCoordinatorRules(),
            'research_work' => $this->researchWorkRules(),
            'research_details' => $this->researchDetailsRules(),
            'inclusion_and_exclusion_criteria' => $this->inclusionExclusionRules(),
            'study_participation_duration' => $this->studyParticipationRules(),
            'communication_publication' => $this->communicationPublicationRules(),
            default => [],
        };
    }

    public static function allRules(): array
    {
        return array_merge(
            (new static)->voucherCodeAndCategory(),
            (new static)->principalInvestigatorRules(),
            (new static)->studyCoordinatorRules(),
            (new static)->researchWorkRules(),
            (new static)->researchDetailsRules(),
            (new static)->inclusionExclusionRules(),
            (new static)->studyParticipationRules(),
            (new static)->communicationPublicationRules(),
        );
    }

    public function voucherCodeAndCategory(): array
    {
        return [
            'voucher_code' => [
                'required',
                Rule::exists('vouchers', 'code')
                    ->where(function ($query) {
                        $query->whereNull('used_at')
                            ->where('is_used', false);
                    })
                    ->where('category_id', $this->input('category_id')),
            ],
            'category_id'  => ['required', 'exists:categories,id'],
        ];

    }
    private function principalInvestigatorRules(): array
    {
        return [
            'title' => ['required', Rule::in(Title::toArray())],
            'surname' => ['required', 'string'],
            'other_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'telephone' => ['required', 'string'],
            'postal_address' => ['required', 'string'],
            'institution_investigator' => ['required', Rule::in(InstitutionInvestigator::toArray())],
            'department_unit' => ['required', 'string'],
            'directorate' => ['required', 'string'],
        ];
    }

    private function studyCoordinatorRules(): array
    {
        return [
            'study_coordinator_name' => ['required', 'string'],
            'coordinator_email' => ['required', 'email'],
            'local_collaborator_name' => ['required', 'string'],
            'local_collaborator_address' => ['required', 'string'],
            'staff_categories' => ['required', 'array'],
            'staff_categories.*' => [Rule::in(StaffCategory::toArray())],
        ];
    }

    private function researchWorkRules(): array
    {
        return [
          //  'research_category' => ['required', 'string'],
            'observation_study' => ['required', 'string'],
            'interventional_study' => ['required', 'string'],
            'study_location_level' => ['required', 'string'],
            'documents' => ['required', 'array'],
        ];
    }

    private function researchDetailsRules(): array
    {
        return [
            'study_location_in_sth' => ['required', 'string'],
            'study_title' => ['required', 'string'],
            'study_design' => ['required', 'array'],
            'study_objectives' => ['required', 'string'],
        ];
    }

    private function inclusionExclusionRules(): array
    {
        return [
            'inclusion_criteria' => ['required', 'string'],
            'exclusion_criteria' => ['required', 'string'],
            'sample_size' => ['required', 'numeric'],
        ];
    }

    private function studyParticipationRules(): array
    {
        return [
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
            'physical_financial_support' => ['required', 'string'],
        ];
    }

    private function communicationPublicationRules(): array
    {
        return [
            'organize_forum' => ['required', Rule::in(YesNo::toArray())],
            'post_on_sth_website' => ['required', Rule::in(YesNo::toArray())],
            'agreement' => ['required', 'boolean'],
        ];
    }
}
