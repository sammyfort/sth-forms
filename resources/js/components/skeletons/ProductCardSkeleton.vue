<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
</script>

<template>
    <Card class="transform cursor-pointer p-2 bg-gray-50 rounded-none border-none shadow-none">
        <CardContent class="flex flex-col gap-2 p-0 pb-4">
            <div class="relative h-65 overflow-hidden bg-gray-200 animate-pulse rounded-xs">
                <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
            </div>

            <div class="h-4 bg-gray-200 rounded animate-pulse w-3/4"></div>

            <div class="flex flex-col gap-1.5">
                <div class="flex items-center gap-3">
                    <div class="h-5 w-20 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-10 bg-gray-200 rounded animate-pulse"></div>
                    <div class="ms-auto h-5 w-5 bg-gray-200 rounded-full animate-pulse"></div>
                </div>

                <div class="text-sm space-y-2 mt-2">
                    <div class="h-4 w-3/4 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-1/2 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-1/3 bg-gray-200 rounded animate-pulse"></div>
                </div>

                <div class="h-5 w-5 bg-gray-200 rounded-full animate-pulse mt-2"></div>

                <div class="mt-1">
                    <div class="h-6 w-24 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
