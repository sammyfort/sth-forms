<script setup lang="ts">
import { Upload, XIcon, Image } from 'lucide-vue-next';

interface Props {
    form: any;
    preview: string | null;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    handleImage: [event: Event];
    removeImage: [];
}>();
</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-primary rounded-lg">
                <Image class="h-5 w-5 text-white" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Featured Image</h3>
        </div>

        <div class="relative">
            <div class="relative rounded-lg border-2 border-dashed border-gray-300 p-6 transition-all duration-200 hover:border-primary hover:bg-blue-50/50 group">
                <input
                    type="file"
                    accept="image/*"
                    @change="emit('handleImage', $event)"
                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                />

                <div v-if="!preview" class="text-center">
                    <Upload class="mx-auto h-12 w-12 text-gray-400 group-hover:text-primary transition-colors duration-200" />
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-900">Upload featured image</p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 10MB</p>
                    </div>
                </div>

                <div v-else class="relative">
                    <img :src="preview" class="w-full h-40 rounded-lg object-cover" />
                    <button
                        type="button"
                        @click="emit('removeImage')"
                        class="absolute top-2 right-2 p-1 bg-red-100 hover:bg-red-200 rounded-full shadow-lg transition-colors duration-200"
                    >
                        <XIcon class="h-4 w-4 text-red-600" />
                    </button>
                    <div class="absolute bottom-2 left-2 px-2 py-1 bg-black/50 rounded text-white text-xs">
                        Featured
                    </div>
                </div>
            </div>

            <p v-if="form.errors.featured_image" class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                <XIcon class="h-4 w-4" />
                <span>{{ form.errors.featured_image }}</span>
            </p>
        </div>
    </div>
</template>
