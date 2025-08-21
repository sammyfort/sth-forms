<script setup lang="ts">
import InputSelect from '@/components/InputSelect.vue';
import { InputSelectOption } from '@/types';
import { useForm } from '@inertiajs/vue3';
import InputText from '@/components/InputText.vue';

const props = defineProps<{
    form: ReturnType<typeof useForm>;
    yesno: InputSelectOption[];
}>();

const studyDesigns = [
    { label: 'Randomized Controlled Clinical Trials', value: 'Randomized Controlled Clinical Trials' },
    { label: 'RNon-Randomized Controlled Clinical Trial', value: 'Non-Randomized Controlled Clinical Trial' },
    { label: 'Cross-Sectional Study', value: 'Cross-Sectional Study' },
    { label: 'Cohort Study', value: 'Cohort Study' },
    { label: 'Case-Control Study', value: 'Case-Control Study' },
    { label: 'Case Series', value: 'Case Series' },
];
</script>

<template>
    <div class="rounded-xl border bg-white p-6 shadow-sm">
        <h2 class="mb-6 text-lg font-semibold">Step 4: Research Details</h2>
        <div class="grid grid-cols-2 gap-6">
            <InputText :form="props.form" label="Study Location in STH"
                       model="study_location_in_sth" required />

            <InputText :form="props.form" label="Study Tittle"
                       model="study_title" required />

            <InputSelect :form="props.form" label="Study Design"
                         model="study_design"
                         :options="studyDesigns" required searchable />

            <InputText :form="props.form"
                       label="Study Objectives"
                       model="study_objectives" required />
        </div>
    </div>
</template>
