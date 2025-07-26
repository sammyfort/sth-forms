<script setup lang="ts">
import { ref, computed, nextTick, onMounted, onUnmounted } from 'vue';
import { Camera, Maximize2, X, ChevronLeft, ChevronRight } from 'lucide-vue-next';

interface Props {
    featuredUrl?: string | null;
    galleryUrls?: string[];
    title?: string;
    className?: string;
}

const props = withDefaults(defineProps<Props>(), {
    featuredUrl: null,
    galleryUrls: () => [],
    title: 'Image Gallery',
    className: ''
});

const lightboxOpen = ref(false);
const currentImageIndex = ref(0);
const lightboxRef = ref<HTMLElement | null>(null);

const allImages = computed(() => {
    const images = [];
    if (props.featuredUrl) {
        images.push({ url: props.featuredUrl, isFeatured: true });
    }
    if (props.galleryUrls) {
        images.push(...props.galleryUrls.map(url => ({ url, isFeatured: false })));
    }
    return images;
});

const hasImages = computed(() => allImages.value.length > 0);
const hasAdditionalPhotos = computed(() => props.galleryUrls && props.galleryUrls.length > 0);

const openLightbox = (index: number) => {
    currentImageIndex.value = index;
    lightboxOpen.value = true;
    nextTick(() => {
        lightboxRef.value?.focus();
    });
};

const closeLightbox = () => {
    lightboxOpen.value = false;
};

const nextImage = () => {
    currentImageIndex.value = (currentImageIndex.value + 1) % allImages.value.length;
};

const prevImage = () => {
    currentImageIndex.value = (currentImageIndex.value - 1 + allImages.value.length) % allImages.value.length;
};

const getImageIndex = (galleryIndex: number) => {
    return props.featuredUrl ? galleryIndex + 1 : galleryIndex;
};

const handleKeyDown = (e: KeyboardEvent) => {
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') prevImage();
};

const handleBodyScroll = () => {
    if (lightboxOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};

onMounted(() => {
    document.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeyDown);
    document.body.style.overflow = '';
});

import { watch } from 'vue';
watch(lightboxOpen, handleBodyScroll);
</script>


<template>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6" :class="className">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <Camera class="w-5 h-5 text-primary" />
            {{ title }}
        </h3>

        <div v-if="hasImages">
            <div v-if="featuredUrl" class="mb-8">
                <div class="relative group cursor-pointer" @click="openLightbox(0)">
                    <img
                        :src="featuredUrl"
                        alt="Featured Image"
                        class="w-full h-80 object-contain bg-gray-50 rounded-2xl shadow-lg transition-transform duration-300 group-hover:scale-[1.02]"
                    />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 rounded-2xl"></div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-gradient-to-r from-yellow-400 to-primary text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                            Featured
                        </span>
                    </div>
                    <button
                        @click.stop="openLightbox(0)"
                        class="absolute top-4 right-4 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors duration-200"
                    >
                        <Maximize2 class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <div v-if="hasAdditionalPhotos" class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-800">Additional Photos</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    <div
                        v-for="(url, idx) in galleryUrls"
                        :key="idx"
                        class="relative group cursor-pointer"
                        @click="openLightbox(getImageIndex(idx))"
                    >
                        <img
                            :src="url"
                            :alt="`Gallery Image ${idx + 1}`"
                            class="w-full h-32 object-contain bg-gray-50 rounded-xl shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:scale-105"
                        />
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 rounded-xl flex items-center justify-center">
                            <Maximize2 class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Camera class="w-8 h-8 text-gray-400" />
            </div>
            <h4 class="text-lg font-medium text-gray-600 mb-2">No Images Available</h4>
            <p class="text-gray-500">Upload photos to showcase this location.</p>
        </div>

        <Teleport to="body">
            <div
                v-if="lightboxOpen"
                class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4"
                @click="closeLightbox"
                @keydown="handleKeyDown"
                tabindex="0"
                ref="lightboxRef"
            >
                <div class="relative w-full h-full flex items-center justify-center">
                    <img
                        :src="allImages[currentImageIndex]?.url"
                        :alt="`Image ${currentImageIndex + 1}`"
                        class="max-w-full max-h-full object-contain rounded-lg"
                        @click.stop
                    />

                    <button
                        @click="closeLightbox"
                        class="absolute top-4 right-4 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors duration-200 z-10"
                    >
                        <X class="w-6 h-6" />
                    </button>

                    <template v-if="allImages.length > 1">
                        <button
                            @click.stop="prevImage"
                            class="absolute left-4 top-1/2 -translate-y-1/2 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors duration-200 z-10"
                        >
                            <ChevronLeft class="w-6 h-6" />
                        </button>
                        <button
                            @click.stop="nextImage"
                            class="absolute right-4 top-1/2 -translate-y-1/2 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors duration-200 z-10"
                        >
                            <ChevronRight class="w-6 h-6" />
                        </button>
                    </template>

                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-white text-sm z-10">
                        {{ currentImageIndex + 1 }} / {{ allImages.length }}
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>
