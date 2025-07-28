<script setup lang="ts">
import { Check, LoaderCircle } from 'lucide-vue-next';

import { Button } from '@/components/ui/button';


interface Props {
    form: any;
    featuredPreview?: string | null;
    galleryPreviews?: string[];
    galleryItems?: Array<{ url: string; isOriginal: boolean; originalIndex?: number }>;
    galleryErrors?: string[];
    submitText: string;
    processingText: string;
    containerWidth?: string
}

const props = withDefaults(defineProps<Props>(), {
    galleryItems: () => [],
    galleryErrors: () => [],
});

const emit = defineEmits<{
    handleFeaturedImage: [event: Event];
    removeFeatured: [];
    handleGalleryImages: [event: Event];
    removeGalleryImage: [index: number];
    submit: [];
}>();
</script>

<template>
    <div  :class="[containerWidth ?? 'max-w-5xl', 'mx-auto px-4 sm:px-6 lg:px-8 py-8']">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="order-1 lg:order-2 space-y-6">
                <slot name="media-section" />
            </div>

            <div class="order-2 lg:order-1 lg:col-span-2 space-y-6">
                <slot name="form-sections" />

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Review your information and submit when ready</p>
                        <Button
                            :disabled="form.processing"
                            @click="emit('submit')"
                            class="px-8 py-3 bg-gradient-to-r from-primary to-primary hover:from-primary
                            hover:to-orange-300 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl
                            transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                            <Check v-else class="mr-2 h-5 w-5" />
                            {{ form.processing ? processingText : submitText }}
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
