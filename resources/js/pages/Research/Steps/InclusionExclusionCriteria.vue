<script setup lang="ts">
import InputText from '@/components/InputText.vue'
import InputSelect from '@/components/InputSelect.vue'
import { InputSelectOption } from '@/types'
import { useForm } from '@inertiajs/vue3'

const props = defineProps<{
    form: ReturnType<typeof useForm>,
    titles: InputSelectOption[]
    institution_investigators: InputSelectOption[]
}>()
</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-lg font-semibold mb-6">Step 4: Inclusion and Exclusion Criteria</h2>
        <div class="grid grid-cols-2 gap-6">
             <InputText :form="props.form" label="Inclusion Criteria" model="inclusion_criteria" required />
            <InputText :form="props.form" label="Exclusion Criteria" model="exclusion_criteria" required />
            <InputText :form="props.form" label="Sample Size" model="sample_size" required />
        </div>
    </div>
</template>
