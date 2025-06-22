<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useAttrs } from 'vue';

type Props = {
    form?: Record<string, any>;
    model?: string;
    label?: string;
};
const props = defineProps<Props>();
const id = Math.random().toString(36).substring(2, 10);
const attrs = useAttrs()
</script>

<template>
    <div class="grid gap-2" v-if="form">
        <Label :for="id">{{ props.label }} <span class="text-red-500" v-show="label && attrs.hasOwnProperty('required')">*</span></Label>
        <Input
            :id="id"
            :value="props.form[props.model]"
            v-model="props.form[props.model]"
            v-bind="attrs"
        />
        <InputError :message="form.errors[model]" />
    </div>
    <div class="grid gap-2" v-else>
        <Label :for="id">{{ props.label }}</Label>
        <Input :id="id" v-bind="attrs" />
    </div>
</template>

<style scoped></style>
