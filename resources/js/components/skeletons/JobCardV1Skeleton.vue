<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
</script>

<template>
    <Card class="shadow-none border-3 rounded-3xl border-gray-300 animate-pulse">
        <CardContent class="h-full">
            <div class="flex gap-7 flex-col h-full">
                <div class="flex items-center gap-3">
                    <div>
                        <div class="w-[60px] h-[60px] rounded-xl bg-gray-300"></div>
                    </div>
                    <div class="flex flex-col gap-1 flex-grow">
                        <div class="w-32 h-4 bg-gray-300 rounded"></div>
                        <div class="flex gap-1 items-center">
                            <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                            <div class="w-24 h-3 bg-gray-300 rounded"></div>
                        </div>
                    </div>
                    <div class="ms-auto">
                        <div class="w-[22px] h-[22px] bg-gray-300 rounded-full"></div>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="w-40 h-4 bg-gray-300 rounded"></div>
                    <div class="flex gap-4 items-center">
                        <div class="flex gap-1 items-center">
                            <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                            <div class="w-16 h-3 bg-gray-300 rounded"></div>
                        </div>
                        <div class="flex gap-1 items-center">
                            <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                            <div class="w-16 h-3 bg-gray-300 rounded"></div>
                        </div>
                    </div>
                    <div class="w-full h-3 bg-gray-300 rounded"></div>
                    <div class="w-5/6 h-3 bg-gray-300 rounded"></div>
                </div>
                <div class="mt-auto">
                    <div class="w-full h-9 bg-gray-300 rounded-3xl"></div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
