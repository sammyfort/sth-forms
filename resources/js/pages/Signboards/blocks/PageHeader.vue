<script setup lang="ts">
interface Props {
    title: string;
    subtitle: string;
    icon: any;
}

const props = defineProps<Props>();
</script>

<template>
    <div class="bg-white border-b border-gray-200 shadow-sm rounded-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-primary rounded-lg">
                    <component :is="icon" class="h-6 w-6 text-white" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ subtitle }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
