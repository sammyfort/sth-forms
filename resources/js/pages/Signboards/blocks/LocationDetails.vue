<script setup lang="ts">

import { MapPin } from 'lucide-vue-next';
import { SignboardI } from '@/types';
const props = defineProps<{
    signboard: SignboardI
}>()
</script>

<template>
    <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
        <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
            <MapPin class="h-5 w-5 text-primary" />Location Details
        </h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between  border-b border-gray-100 py-2">
                <span class="font-medium text-gray-600">Region</span>
                <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-gray-900 text-primary">{{ props.signboard.region.name }}
                </span>
            </div>
            <div class="flex items-center justify-between border-b border-gray-100 py-2">
                <span class="font-medium text-gray-600">Town</span>
                <span class="font-mono text-sm text-gray-900">{{ props.signboard.town }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-gray-100 py-2">
                <span class="font-medium text-gray-600">Street</span>
                <span class="text-gray-900">{{ props.signboard.street || '—' }}</span>
            </div>

            <div class="flex items-center justify-between border-b border-gray-100 py-2">
                <span class="font-medium text-gray-600">Landmark</span>
                <span class="text-gray-900">{{ props.signboard.landmark || '—' }}</span>
            </div>

            <div class="flex items-center justify-between border-b border-gray-100 py-2">
                <span class="font-medium text-gray-600">Block Number</span>
                <span class="text-gray-900">{{ props.signboard.blk_number || '—' }}</span>
            </div>

            <div class="flex items-center justify-between border-b border-gray-100 py-2">
                <span class="font-medium text-gray-600">GPS</span>
                <span class="font-mono text-sm text-gray-900">{{ props.signboard.gps }}</span>
            </div>




        </div>
    </div>
</template>

<style scoped>

</style>
