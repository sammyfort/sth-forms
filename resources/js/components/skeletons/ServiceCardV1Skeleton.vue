<script setup lang="ts">

import { Card, CardContent, CardHeader } from '@/components/ui/card';
</script>

<template>
    <Card
        class="shadow-sm border-0 py-2 rounded-none gap-1 animate-pulse"
    >
        <CardHeader>
            <div class="overflow-hidden">
                <div class="h-4 w-3/4 bg-gray-200 rounded mb-1"></div>
                <div class="space-y-1">
                    <div class="h-3 w-1/2 bg-gray-200 rounded"></div>
                    <div class="h-3 w-2/3 bg-gray-100 rounded"></div>
                </div>
            </div>
        </CardHeader>

        <CardContent>
            <div class="relative">
                <div class="w-full h-[120px] bg-gray-300 rounded"></div>
                <div class="absolute bottom-[0px] right-0 px-2 bg-gray-400 text-sm text-white rounded">
                    &nbsp;
                </div>
            </div>
            <div class="mt-3">
                <div class="flex gap-2 text-secondary items-center">
                    <div class="w-[15px] h-[15px] bg-gray-300 rounded-full"></div>
                    <div>
                        <div class="h-3 w-24 bg-gray-200 rounded mb-1"></div>
                        <div class="h-2 w-32 bg-gray-100 rounded"></div>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
