<script setup lang="ts">

import { Zap } from 'lucide-vue-next';
import { SignboardI } from '@/types';
const props = defineProps<{
    signboard: SignboardI
}>()
</script>

<template>
    <div class="mb-3 rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
        <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
            <Zap class="h-5 w-5 text-primary" />
            Fields of Operation
        </h3>
        <div class="flex flex-wrap gap-2">
            <span v-if="props.signboard.categories.length"
                  v-for="category in props.signboard.categories"
                  :key="category.id"
                  class="rounded-full bg-primary px-3 py-1 text-sm font-medium text-white">{{ category.name }}
            </span>
            <span v-else class="text-red rounded-full px-3 py-1 text-sm font-medium">No Field</span>
        </div>
    </div>
</template>

<style scoped>

</style>
