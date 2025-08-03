<template>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">{{ title }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ value }}</p>
                <p v-if="subtext" class="text-sm text-gray-500">{{ subtext }}</p>

<!--                <p :class="`text-sm font-medium   text-black`">{{ subtitle }}</p>-->

            </div>
            <div :class="['h-12 w-12 rounded-lg flex items-center justify-center', iconBg]">
                <component :is="icon" class="h-6 w-6" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import type { Component } from 'vue';

const props = defineProps<{
    title: string;
    value: string | number;
    subtext?: string;
    iconBg: string;
    icon: Component;
    subtitle?: string|number
}>();
</script>

<style scoped>

</style>
