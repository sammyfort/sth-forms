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

import { useAttrs, ref, computed, watch } from 'vue'

type Option = {
    label: string
    value: string
}

type Props = {
    form?: Record<string, any>
    model?: string
    label?: string
    options: Option[]
    searchable?: boolean
}

const props = defineProps<Props>()
const attrs = useAttrs()
const id = Math.random().toString(36).substring(2, 10)

const searchQuery = ref('')
const filteredOptions = computed(() => {
    if (!props.searchable || !searchQuery.value) return props.options
    return props.options.filter(o =>
        o.label.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

const open = ref(false)
watch(open, (val) => {
    if (!val) searchQuery.value = ''
})
</script>

<template>
    <div class="grid gap-2">
        <Label :for="id">
            {{ props.label }}
            <span class="text-red-500" v-show="props.label && attrs.hasOwnProperty('required')">*</span>
        </Label>

        <Select v-if="props.form && props.model" v-model="props.form[props.model]" @open-change="open = $event">
            <SelectTrigger :id="id" class="w-full">
                <SelectValue :placeholder="attrs.placeholder || 'Select an option'" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <div v-if="props.searchable" class="p-2">
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Search..."
                            class="w-full px-2 py-1 border border-gray-300 rounded"
                        />
                    </div>

                    <SelectItem
                        v-for="option in filteredOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </SelectItem>
                    <div v-if="props.searchable && filteredOptions.length === 0" class="p-2 text-gray-500">
                        No results found.
                    </div>
                </SelectGroup>
            </SelectContent>
        </Select>

        <InputError v-if="props.form && props.model" :message="props.form.errors[props.model]" />
    </div>
</template>
