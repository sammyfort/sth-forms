<script setup lang="ts">
import InputSelect from '@/components/InputSelect.vue';
import InputText from '@/components/InputText.vue';
import { useForm } from '@inertiajs/vue3';
import { Category, InputSelectOption } from '@/types';

const props = defineProps<{
    form: ReturnType<typeof useForm>;
    yesno: InputSelectOption[];
    categories: Category[]
}>();
</script>

<template>
    <div class="rounded-xl border bg-white p-6 shadow-sm">
        <h2 class="mb-6 text-lg font-semibold">Begin: Research Category</h2>
        <div class="grid grid-cols-2 gap-6">
            <InputText :form="props.form"
                         label="Voucher Code"
                         model="voucher_code"
                           />

            <InputSelect :form="props.form"
                         label="Under which category does your research fall? "
                         model="observation_study"
                         :options="categories" required searchable />



        </div>
    </div>
</template>

<style scoped></style>
