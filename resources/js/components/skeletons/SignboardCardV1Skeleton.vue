<script setup lang="ts">

</script>

<template>
    <div class="overflow-hidden relative rounded-lg bg-white shadow animate-pulse">
        <!-- Fake Badge -->
        <div class="absolute top-3 right-3 p-1 rounded-full bg-gray-300 w-6 h-6"></div>

        <!-- Image Placeholder -->
        <div class="bg-gray-200 w-full h-[160px]"></div>

        <!-- Body -->
        <div class="p-4 h-[250px] flex flex-col gap-2">
            <!-- Categories -->
            <div class="flex flex-wrap gap-2 text-xs">
                <div v-for="n in 2" :key="n" class="flex items-center space-x-1">
                    <div class="h-3 w-3 rounded-full bg-gray-300"></div>
                    <div class="h-3 w-16 bg-gray-300 rounded"></div>
                </div>
            </div>

            <!-- Business Name -->
            <div class="mt-2 h-5 w-3/4 bg-gray-300 rounded"></div>

            <!-- Location Info -->
            <div class="mt-2 flex flex-col gap-2">
                <div class="h-3 w-1/2 bg-gray-300 rounded"></div>
                <div class="h-3 w-1/3 bg-gray-300 rounded"></div>
                <div class="h-3 w-1/3 bg-gray-300 rounded"></div>
            </div>

            <!-- Stars + Reviews -->
            <div class="mt-2 flex items-center space-x-3">
                <div class="h-4 w-24 bg-gray-300 rounded"></div>
                <div class="h-3 w-16 bg-gray-300 rounded"></div>
            </div>

            <!-- Footer Buttons -->
            <div class="mt-auto w-full flex gap-3">
                <div class="h-8 w-24 bg-gray-300 rounded"></div>
                <div class="ms-auto h-8 w-24 bg-gray-300 rounded"></div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
