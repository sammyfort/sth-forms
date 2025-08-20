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
        <h2 class="text-lg font-semibold mb-6">Step 1: Details of Principal Investigator</h2>
        <div class="grid grid-cols-2 gap-6">
            <InputSelect :form="props.form" label="Title" model="title" :options="props.titles" required searchable />
            <InputText :form="props.form" label="Surname" model="surname" required />
            <InputText :form="props.form" label="Other Names" model="other_name" required />
            <InputText :form="props.form" label="Email address" model="email" required />
            <InputText :form="props.form" label="Postal address" model="postal_address" required />
            <InputText :form="props.form" label="Telephone" model="telephone" required />
            <InputSelect :form="props.form" label="Institution of Investigator" model="institution_investigator"
                         :options="props.institution_investigators" required searchable />
            <InputText :form="props.form" label="Department/Unit" model="department_unit" required />
            <InputText :form="props.form" label="Directorate" model="directorate" required />
        </div>
    </div>
</template>
