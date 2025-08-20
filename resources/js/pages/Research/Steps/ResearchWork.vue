<script setup lang="ts">
import InputSelect from '@/components/InputSelect.vue';
import { InputSelectOption } from '@/types';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    form: ReturnType<typeof useForm>;
    yesno: InputSelectOption[];
}>();
</script>

<template>
    <div class="rounded-xl border bg-white p-6 shadow-sm">
        <h2 class="mb-6 text-lg font-semibold">Step 3: About the Research Work</h2>
        <div class="grid grid-cols-2 gap-6">
            <InputSelect :form="props.form" label="Under which category does your research fall?" model="research_category"
                         :options="props.yesno" required searchable />
            <InputSelect :form="props.form" label="Is the work an observational study? " model="observation_study"
                         :options="props.yesno" required searchable />
            <InputSelect :form="props.form" label="Is the work an interventional study? " model="interventional_study"
                         :options="props.yesno" required searchable />

            <InputSelect :form="props.form" label="Where do you intend to conduct the study?" model="study_location_level"
                         :options="props.yesno" required searchable />

            <InputSelect :form="props.form" label="If the project is a clinical trial, please tick if you have
            the following documents or need one" model="documents"
                         :options="props.yesno" required  taggable />
        </div>
    </div>
</template>
