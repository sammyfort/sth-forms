<script setup lang="ts">
import { Camera, Maximize2 } from 'lucide-vue-next';
import { SignboardI } from '@/types';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import { HTMLAttributes } from 'vue';

type Props = {
    signboard: SignboardI;
    class?: HTMLAttributes['class']
};

const props = defineProps<Props>();
</script>

<template>
    <Card :class="cn('', props.class)">
        <CardHeader class="flex gap-2 items-center">
            <Camera class="h-5 w-5 text-primary" />
            <CardTitle class="text-lg">Signboard Gallery</CardTitle>
        </CardHeader>
        <CardContent>
            <div v-if="signboard.featured_url" class="mb-8">
                <div class="group relative">
                    <img
                        :src="signboard.featured_url"
                        alt="Featured Signboard"
                        class="h-80 w-full rounded-2xl object-cover shadow-lg transition-transform duration-300 group-hover:scale-[1.02]"
                    />
                    <div class="absolute inset-0 rounded-2xl bg-black/0 transition-colors duration-300 group-hover:bg-black/10"></div>
                    <div class="absolute top-4 left-4">
                    <span class="rounded-full bg-gradient-to-r from-yellow-400 to-primary px-3 py-1 text-sm font-medium text-white shadow-lg">
                        Featured
                    </span>
                    </div>
                    <button
                        class="absolute top-4 right-4 rounded-full bg-white/20 p-2 text-white backdrop-blur-sm transition-colors duration-200 hover:bg-white/30"
                    >
                        <Maximize2 class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <div v-if="signboard.gallery_urls?.length" class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-800">Additional Photos</h4>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
                    <div v-for="(url, idx) in signboard.gallery_urls" :key="idx" class="group relative cursor-pointer">
                        <img
                            :src="url"
                            alt="Signboard Gallery"
                            class="h-32 w-full rounded-xl object-cover shadow-md transition-all duration-300 group-hover:scale-105 group-hover:shadow-xl"
                        />
                        <div
                            class="absolute inset-0 flex items-center justify-center rounded-xl bg-black/0 transition-colors duration-300 group-hover:bg-black/20"
                        >
                            <Maximize2 class="h-5 w-5 text-white opacity-0 transition-opacity duration-300 group-hover:opacity-100" />
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!signboard.featured_url && !signboard.gallery_urls?.length" class="py-12 text-center">
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100">
                    <Camera class="h-8 w-8 text-gray-400" />
                </div>
                <h4 class="mb-2 text-lg font-medium text-gray-600">No Images Available</h4>
                <p class="text-gray-500">Upload photos to showcase this signboard location.</p>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped></style>
