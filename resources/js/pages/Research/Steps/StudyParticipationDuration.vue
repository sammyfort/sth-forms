<script setup lang="ts">
import InputSelect from '@/components/InputSelect.vue';
import InputText from '@/components/InputText.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    form: ReturnType<typeof useForm>;

}>();

const fundbase = [
    {label: 'STH', value: 'STH'},
    {label: 'Other', value: 'Other'},
]
</script>

<template>
    <div class="rounded-xl border bg-white p-6 shadow-sm">
        <h2 class="mb-6 text-lg font-semibold">Step 6: Study Participation Duration</h2>
        <div class="grid grid-cols-2 gap-6">
            <InputText :form="props.form" type="date"
                         label="Proposed Start Date" model="proposed_start_date"
                         required  />

            <InputText :form="props.form" type="date"
                       label="Proposed End Date" model="proposed_end_date" required />

            <InputText :form="props.form" label="Proposed duration of study"
                       model="proposed_study_duration" required />

            <InputText :form="props.form" type="date" label="Recruitment Start Date"
                       model="recruitment_start_date" required />

            <InputText :form="props.form"  type="date" label="Recruitment End Date"
                       model="recruitment_end_date" required />

            <InputText :form="props.form" label="Source of Funding / Funding Agency"
                       model="source_of_fund" required />

            <InputSelect
                :form="props.form"
                label="Where will Research Funds be Based?"
                model="research_fund_base"
                :options="fundbase"
                required
                searchable
            />

            <InputText :form="props.form"
                       label="Declare Total Budget for Running Study in STH"
                       model="research_total_budget" required />

            <InputText :form="props.form"
                       label="What support would the Study need from STH?"
                       model="study_support_from_sth" required />

            <InputText :form="props.form"
                       label="Based on your research protocol, which equipment(s)
            in STH will you use for your research?" model="equipments_needed" required />

            <InputText :form="props.form"
                       label="What physical or financial support do you anticipate
             to provide STH during and after the end of Study?"
                       model="physical_financial_support" required />



        </div>
    </div>
</template>
