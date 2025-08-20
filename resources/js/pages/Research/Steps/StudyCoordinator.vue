<script setup lang="ts">
import InputText from '@/components/InputText.vue'
import InputSelect from '@/components/InputSelect.vue'
import { Category } from '@/types'
import { useForm } from '@inertiajs/vue3'

const props = defineProps<{
    form: ReturnType<typeof useForm>,
    categories: Category[]
}>()
</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-lg font-semibold mb-6">Step 2: Application Info</h2>
        <div class="grid grid-cols-2 gap-6">
            <InputText :form="props.form" label="Voucher Code" model="voucher_code" required />
            <InputSelect :form="props.form" label="Category" model="category_id" :options="props.categories" required searchable />
        </div>
    </div>
</template>
