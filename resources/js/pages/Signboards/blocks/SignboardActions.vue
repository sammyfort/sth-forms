<script setup lang="ts">

import { ExternalLink, Navigation, Share2, Zap } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { SignboardI } from '@/types';
const props = defineProps<{
    signboard: SignboardI
}>()
</script>

<template>
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
        <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
            <Zap class="h-5 w-5 text-primary" />
            Quick Actions
        </h3>
        <div class="space-y-3">
            <a
                :href="`https://maps.google.com/?q=${props.signboard.gps}`"
                target="_blank"
                class="group flex items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"
            >
                <div class="rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110">
                    <ExternalLink class="h-4 w-4 text-white" />
                </div>
                <span class="font-medium">View on Google Maps</span>
            </a>
            <button
                class="group flex w-full items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"
            >
                <div class="rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110">
                    <Share2 class="h-4 w-4 text-white" />
                </div>
                <span class="font-medium">Share Location</span>
            </button>
            <button
                class="group flex w-full items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"
            >
                <div class="rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110">
                    <Navigation class="h-4 w-4 text-white" />
                </div>
                <span class="font-medium">Get Directions</span>
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
