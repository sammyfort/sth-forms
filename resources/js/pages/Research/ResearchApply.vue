<script setup lang="ts">
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Building2 } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';
import FormComponent from '@/components/FormComponent.vue';
import { Category } from '@/types';
import { Button } from '@/components/ui/button';
import PrincipalInvestigator from '@/pages/Research/Steps/PrincipalInvestigator.vue';
import StudyCoordinator from '@/pages/Research/Steps/StudyCoordinator.vue';
import ResearchWork from '@/pages/Research/Steps/ResearchWork.vue';
import StudyLocationLevel from '@/pages/Research/Steps/StudyLocationLevel.vue';
import ResearchDetails from '@/pages/Research/Steps/ResearchDetails.vue';
import InclusionExclusionCriteria from '@/pages/Research/Steps/InclusionExclusionCriteria.vue';
import ResearchApplicationForm from '@/pages/Research/Steps/ResearchApplicationForm.vue';
import StudyParticipationDuration from '@/pages/Research/Steps/StudyParticipationDuration.vue';
import CommunicationPublication from '@/pages/Research/Steps/CommunicationPublication.vue';

const props = defineProps<{
    categories: Category[]
}>()

const steps: Record<string, any> = {
    principal_investigator: PrincipalInvestigator,
    study_coordinator: StudyCoordinator,
    research_work: ResearchWork,
    study_location_level: StudyLocationLevel,
    Research_details: ResearchDetails,
    inclusion_and_exclusion_criteria: InclusionExclusionCriteria,
    research_application_form: ResearchApplicationForm,
    study_participation_duration: StudyParticipationDuration,
    communication_publication: CommunicationPublication

}

// list of step keys
const stepKeys = Object.keys(steps)
const currentStep = ref(stepKeys[0])




const form = useForm({
    voucher_code: null,
    category_id: null,
    letter: null,
})

const nextStep = () => {
    form.post(route('research.validateStep', { step: currentStep.value }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            if (Object.keys(form.errors).length === 0) {
                const index = stepKeys.indexOf(currentStep.value)
                if (index < stepKeys.length - 1) {
                    currentStep.value = stepKeys[index + 1]
                }
            }
        },
    })
}

const prevStep = () => {
    const index = stepKeys.indexOf(currentStep.value)
    if (index > 0) {
        currentStep.value = stepKeys[index - 1]
    }
}

const submitApplication = () => {
    form.post(route('research.apply'), {
        onSuccess: () => {
            toastSuccess('Application Submitted')
            form.reset()
        },
        onError: () => {
            toastError('Something went wrong')
        },
    })
}
</script>

<template>
    <Head title="Research Application" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Apply For research at STH"
                subtitle="Submit your details to to apply"
                :icon="Building2"
            />

            <FormComponent
                :form="form"
                submit-text="Submit"
                processing-text="Please wait..."
                @submit="submitApplication"
                container-width="max-w-8xl"
            >
                <template #form-sections>
                    <component
                        :is="steps[currentStep]"
                        :form="form"
                        :categories="props.categories"
                    />

                    <div class="flex justify-between w-full mt-6">
                        <Button v-if="currentStep !== stepKeys[0]" type="button" @click="prevStep">
                            Back
                        </Button>

                        <Button
                            v-if="currentStep !== stepKeys[stepKeys.length - 1]"
                            type="button"
                            class="ml-auto"
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
