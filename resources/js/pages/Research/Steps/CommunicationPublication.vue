<script setup lang="ts">
import InputSelect from '@/components/InputSelect.vue'
import { InputSelectOption } from '@/types'
import { useForm } from '@inertiajs/vue3'

const props = defineProps<{
    form: ReturnType<typeof useForm>,
    yesno: InputSelectOption[]

}>()

const agreement = [
    {label: 'I agree', value: true}
]
</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-lg font-semibold mb-6">Step 7:Communication and Publication</h2>
        <div class="grid grid-cols-2 gap-6">
            <InputSelect :form="props.form"
                         label="Would you want the STH Research and Development unit to organize a scientific forum
                         for you to present your research protocol to STH scientific community?"
                         model="organize_forum"
                         :options="props.yesno" required  />

            <InputSelect :form="props.form"
                         label="Would you want your research
                         finding(s) to be posted on the STH website?" model="post_on_sth_website"
                         :options="props.yesno" required  />



            <InputSelect :form="props.form"
                         label="I hereby confirm that the information
                          provided is true, complete, and accurate to the
                          best of my knowledge. I understand that providing
                          false or misleading information to obtain R&D
                          approval or to conduct studies within STH is strictly
                          prohibited. STH reserves the right to suspend or
                          terminate any study if it is later discovered that
                          falsified information was submitted. Please review t
                          he statement above and click the
                          'I Agree' button below to indicate your agreement."
                         model="agreement"
                         :options="agreement" required  />
        </div>
    </div>
</template>
