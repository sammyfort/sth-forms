<script setup lang="ts">
import InputError from '@/components/InputError.vue'
import { Label } from '@/components/ui/label'
import {
    Select,
    SelectTrigger,
    SelectValue,
    SelectContent,
    SelectGroup,
    SelectLabel,
    SelectItem
} from '@/components/ui/select'

import { useAttrs } from 'vue'

type Option = {
    label: string
    value: string
}

type Props = {
    form?: Record<string, any>
    model?: string
    label?: string
    options: Option[]
}

const props = defineProps<Props>()
const id = Math.random().toString(36).substring(2, 10)
const attrs = useAttrs()
</script>

<template>
    <div class="grid gap-2">
        <Label :for="id">
            {{ props.label }}
            <span class="text-red-500" v-show="props.label && attrs.hasOwnProperty('required')">*</span>
        </Label>

        <Select v-if="props.form && props.model" v-model="props.form[props.model]">
            <SelectTrigger :id="id" class="w-full">
                <SelectValue :placeholder="attrs.placeholder || 'Select an option'" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem
                        v-for="option in props.options"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>

        <InputError v-if="props.form && props.model" :message="props.form.errors[props.model]" />
    </div>
</template>
