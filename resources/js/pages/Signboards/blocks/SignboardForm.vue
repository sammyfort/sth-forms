<script setup lang="ts">
import { Building2, MapPin, Check, LoaderCircle } from 'lucide-vue-next';
import InputText from '@/components/InputText.vue';
import InputSelect from '@/components/InputSelect.vue';
import ImageUpload from './ImageUpload.vue';
import GalleryUpload from './GalleryUpload.vue';
import { Button } from '@/components/ui/button';

interface Props {
    form: any;
    categories: Array<{ label: string, value: string }>;
    regions: Array<{ label: string, value: string }>;
    businesses: Array<{ label: string, value: string }>;
    businessFieldDisabled?: boolean;
    featuredPreview: string | null;
    galleryPreviews: string[];
    galleryItems?: Array<{url: string, isOriginal: boolean, originalIndex?: number}>;
    submitText: string;
    processingText: string;
    galleryErrors?: string[];
}

const props = withDefaults(defineProps<Props>(), {
    businessFieldDisabled: false,
    galleryItems: () => [],
    galleryErrors: () => []
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
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="order-1 lg:order-2 space-y-6">
                <ImageUpload
                    :form="form"
                    :preview="featuredPreview"
                    @handle-image="emit('handleFeaturedImage', $event)"
                    @remove-image="emit('removeFeatured')"
                />
                <GalleryUpload
                    :form="form"
                    :previews="galleryPreviews"
                    :gallery-items="galleryItems"
                    :gallery-errors="galleryErrors"
                    @handle-images="emit('handleGalleryImages', $event)"
                    @remove-image="emit('removeGalleryImage', $event)"
                />
            </div>

            <div class="order-2 lg:order-1 lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-primary rounded-lg">
                            <Building2 class="h-5 w-5 text-white" />
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Business Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <InputSelect
                                label="Select Business"
                                :form="form"
                                model="business_id"
                                :disabled="businessFieldDisabled"
                                :options="businesses"
                                required
                                searchable
                            />

                            <InputText
                                :form="form"
                                label="Name/Title"
                                model="name"
                                required
                            />



                        <div class="md:col-span-2">
                            <InputSelect
                                label="Signboard Categories"
                                :form="form"
                                model="categories"
                                :options="categories"
                                taggable
                                required
                                searchable
                            />
                        </div>
                        <InputSelect
                            label="Region"
                            :form="form"
                            model="region_id"
                            :options="regions"
                            required
                            searchable
                        />
                        <InputText
                            :form="form"
                            label="Town"
                            model="town"
                            required
                        />
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-primary rounded-lg">
                            <MapPin class="h-5 w-5 text-white" />
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Location Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <InputText
                            :form="form"
                            label="Street Address"
                            model="street"
                            placeholder="e.g., Main Street"
                        />
                        <InputText
                            :form="form"
                            label="Landmark"
                            model="landmark"
                            required
                            placeholder="e.g., Near Central Mall"
                        />
                        <InputText
                            :form="form"
                            label="Block Number"
                            model="blk_number"
                            placeholder="e.g., Block A"
                        />
                        <InputText
                            :form="form"
                            label="GPS Coordinates"
                            model="gps"
                            placeholder="e.g., 5.6037, -0.1870"
                        />
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mt-1">Review your information and submit when ready</p>
                        </div>
                        <Button
                            :disabled="form.processing"
                            @click="emit('submit')"
                            class="px-8 py-3 bg-gradient-to-r from-primary to-primary hover:from-primary hover:to-orange-300 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
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
