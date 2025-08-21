<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Building2 } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';
import FormComponent from '@/components/FormComponent.vue';
import { Category, InputSelectOption } from '@/types';
import { Button } from '@/components/ui/button';
import PrincipalInvestigator from '@/pages/Research/Steps/PrincipalInvestigator.vue';
import StudyCoordinator from '@/pages/Research/Steps/StudyCoordinator.vue';
import ResearchWork from '@/pages/Research/Steps/ResearchWork.vue';
import ResearchDetails from '@/pages/Research/Steps/ResearchDetails.vue';
import InclusionExclusionCriteria from '@/pages/Research/Steps/InclusionExclusionCriteria.vue';
import StudyParticipationDuration from '@/pages/Research/Steps/StudyParticipationDuration.vue';
import CommunicationPublication from '@/pages/Research/Steps/CommunicationPublication.vue';
import VoucherValidation from '@/pages/Research/Steps/VoucherValidation.vue';

const props = defineProps<{
    categories: Category[];
    titles: InputSelectOption[];
    institution_investigators: InputSelectOption[];
    staff_categories: InputSelectOption[];
    yesno: InputSelectOption[]
}>();


const form = useForm({
    voucher_code: '',
    category_id: '',

    // principal investigator
    title: '',
    surname: '',
    other_name: '',
    email: '',
    telephone: '',
    postal_address: '',
    institution_investigator: '',
    department_unit: '',
    directorate: '',

    // research study coordinator
    study_coordinator_name: '',
    coordinator_email: '',
    local_collaborator_name: '',
    local_collaborator_address: '',
    staff_categories: [],

    // research work
   // research_category: '',
    observation_study: '',
    interventional_study: '',
    study_location_level: '',
    documents: [],

    // research details
    study_location_in_sth: '',
    study_title: '',
    study_design: [],
    study_objectives: '',

    // inclusion and exclusion
    inclusion_criteria: '',
    exclusion_criteria: '',
    sample_size: '',

    // study participation
    proposed_start_date: '',
    proposed_end_date: '',
    proposed_study_duration: '',
    recruitment_start_date: '',
    recruitment_end_date: '',
    source_of_fund: '',
    research_fund_base: '',
    research_total_budget: '',
    study_support_from_sth: '',
    equipments_needed: '',
    physical_financial_support: '',

    // communication and publication
    organize_forum: '',
    post_on_sth_website: '',
    agreement: ''

});
const steps: Record<string, any> = {
    voucher_validation: VoucherValidation,
    principal_investigator: PrincipalInvestigator,
    study_coordinator: StudyCoordinator,
    research_work: ResearchWork,
    Research_details: ResearchDetails,
    inclusion_and_exclusion_criteria: InclusionExclusionCriteria,
    study_participation_duration: StudyParticipationDuration,
    communication_publication: CommunicationPublication,
};
const stepKeys = Object.keys(steps);
const currentStep = ref(stepKeys[0]);

const stepProps: Record<string, Record<string, any>> = {
    voucher_validation: {categories: props.categories},
    principal_investigator: {form: form, titles: props.titles, institution_investigators: props.institution_investigators},
    study_coordinator: {staff_categories: props.staff_categories },
    research_work: {yesno: props.yesno },
    research_details: {yesno: props.yesno },
    communication_publication: {yesno: props.yesno },
};
const currentStepProps = computed(() => stepProps[currentStep.value] || {});

onMounted(()=> {
    console.log(props.titles)
})


const nextStep = () => {
    form.post(route('research.validateStep', { step: currentStep.value }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            if (Object.keys(form.errors).length === 0) {
                const index = stepKeys.indexOf(currentStep.value);
                if (index < stepKeys.length - 1) {
                    currentStep.value = stepKeys[index + 1];
                }
            }
        },
    });
};

const prevStep = () => {
    const index = stepKeys.indexOf(currentStep.value);
    if (index > 0) {
        currentStep.value = stepKeys[index - 1];
    }
};

const submitApplication = () => {
    form.post(route('research.submit'), {
        onSuccess: () => {
            form.reset();
            toastSuccess('Application Submitted');

        },
        onError: () => {
            toastError('Something went wrong');
        },
    });
};
</script>

<template>
    <Head title="Research Application" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader title="Apply For research at STH"
                        subtitle="Submit your details to to apply"
                        :icon="Building2"
            />

            <FormComponent :form="form" submit-text="Submit"
                           processing-text="Please wait..."
                           @submit="submitApplication"
                           container-width="max-w-8xl"
                           :ready-for-submit="currentStep === stepKeys[stepKeys.length - 1]"            >
                <template #form-sections>
                    <component :is="steps[currentStep]" :form="form" v-bind="currentStepProps" />

                    <div class="mt-6 flex w-full justify-center space-x-4">
                        <Button
                            variant="outline"
                            v-if="currentStep !== stepKeys[0]"
                            type="button"
                            @click="prevStep"
                        >
                            Back
                        </Button>

                        <Button
                            v-if="currentStep !== stepKeys[stepKeys.length - 1]"
                            type="button"
                            @click.prevent="nextStep"
                        >
                            Next
                        </Button>
                    </div>

                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
