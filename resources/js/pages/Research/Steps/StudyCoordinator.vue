<script setup lang="ts">
import InputText from '@/components/InputText.vue'
import InputSelect from '@/components/InputSelect.vue'
import { InputSelectOption } from '@/types'
import { useForm } from '@inertiajs/vue3'

const props = defineProps<{
    form: ReturnType<typeof useForm>,
    staff_categories: InputSelectOption[]
}>()

</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-lg font-semibold mb-6">Step 2: Details of Co-Investigator(s) / Study Co-ordinator</h2>
        <div class="grid grid-cols-2 gap-6">
            <InputText :form="props.form" label="Name of Co-ordinator" model="study_coordinator_name" required />
            <InputText :form="props.form" label="Email address of Co-ordinator" model="coordinator_email" required />
            <InputText :form="props.form" label="Name of Local Collaborator" model="local_collaborator_name" required />
            <InputText :form="props.form" label="Email of Local Collaborator" model="local_coordinator_address" required />
            <InputSelect :form="props.form" label="Which category/categories of Staff in STH will be involved in the study?"
                         model="staff_categories" :options="props.staff_categories" required searchable taggable />
        </div>
    </div>
</template>
