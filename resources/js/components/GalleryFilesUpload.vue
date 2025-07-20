<script setup lang="ts">
import { ref, watch, onUnmounted } from 'vue';
import { Plus, XIcon } from 'lucide-vue-next';

interface Props {
    form: any;
    galleryItems?: Array<{ url: string; isOriginal: boolean; originalIndex?: number }>;
    galleryErrors?: string[];
}

const props = withDefaults(defineProps<Props>(), {
    galleryItems: () => [],
    galleryErrors: () => [],
});


const files = defineModel<File[]>('files', {
    default: () => []
});

const previews = ref<string[]>([]);

const resetGallery = () => {

    previews.value.forEach(url => {
        if (url.startsWith('blob:')) {
            URL.revokeObjectURL(url);
        }
    });

    files.value = [];
    previews.value = [];
};

defineExpose({
    resetGallery
});


watch(files, (newFiles) => {

    const filesToKeep = newFiles.length;
    while (previews.value.length > filesToKeep) {
        const removedPreview = previews.value.pop();
        if (removedPreview && removedPreview.startsWith('blob:')) {
            URL.revokeObjectURL(removedPreview);
        }
    }


    for (let i = previews.value.length; i < newFiles.length; i++) {
        previews.value.push(URL.createObjectURL(newFiles[i]));
    }
}, { deep: true });

const onFileChange = (e: Event) => {
    const input = e.target as HTMLInputElement;
    const selected = Array.from(input.files || []);

    if (selected.length > 0) {

        const currentFiles = [...files.value];
        selected.forEach(file => {
            currentFiles.push(file);
        });

        files.value = currentFiles;
    }


    input.value = '';
};

const removeFile = (index: number) => {

    const previewUrl = previews.value[index];
    if (previewUrl && previewUrl.startsWith('blob:')) {
        URL.revokeObjectURL(previewUrl);
    }


    const newFiles = [...files.value];
    const newPreviews = [...previews.value];

    newFiles.splice(index, 1);
    newPreviews.splice(index, 1);


    files.value = newFiles;
    previews.value = newPreviews;
};

const removeOriginalImage = (originalIndex: number) => {
    const itemToRemove = props.galleryItems[originalIndex];

    if (itemToRemove && itemToRemove.isOriginal) {

        if (!props.form.removed_gallery_urls.includes(itemToRemove.url)) {
            props.form.removed_gallery_urls.push(itemToRemove.url);
        }
    }
};


onUnmounted(() => {
    previews.value.forEach(url => {
        if (url.startsWith('blob:')) {
            URL.revokeObjectURL(url);
        }
    });
});
</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-primary rounded-lg">
                    <Plus class="h-5 w-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Gallery Images</h3>
            </div>
            <span class="inline-flex items-center whitespace-nowrap px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                {{ (galleryItems.filter(item => !form.removed_gallery_urls?.includes(item.url)).length + files.length) }} images
            </span>
        </div>

        <div class="space-y-4">

            <div class="relative rounded-lg border-2 border-dashed border-gray-300 p-4 transition-all duration-200 hover:border-primary hover:bg-indigo-50/50 group">
                <input
                    type="file"
                    multiple
                    accept="image/*"
                    @change="onFileChange"
                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                />
                <div class="text-center">
                    <Plus class="mx-auto h-8 w-8 text-gray-400 group-hover:text-primary transition-colors duration-200" />
                    <p class="text-sm font-medium text-gray-700 mt-2">Add more images</p>
                    <p class="text-xs text-gray-500">Multiple selection supported</p>
                </div>
            </div>


            <div v-if="galleryItems.length || files.length" class="max-h-96 overflow-y-auto">
                <div class="grid grid-cols-2 gap-3 pr-2">

                    <template v-for="(item, idx) in galleryItems" :key="`original-${idx}`">
                        <div
                            v-if="!form.removed_gallery_urls?.includes(item.url)"
                            class="relative group"
                        >
                            <img
                                :src="item.url"
                                :alt="`Gallery image ${idx + 1}`"
                                class="w-full h-24 rounded-lg object-cover border border-gray-200"
                            />
                            <button
                                type="button"
                                @click="removeOriginalImage(idx)"
                                class="absolute top-1 right-1 p-1 bg-red-100 hover:bg-red-200 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-200"
                            >
                                <XIcon class="h-3 w-3 text-red-600" />
                            </button>
                            <div class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-blue-500/80 rounded text-white text-xs">
                                Existing {{ idx + 1 }}
                            </div>
                        </div>
                    </template>


                    <div
                        v-for="(src, idx) in previews"
                        :key="`new-${idx}`"
                        class="relative group"
                    >
                        <img
                            :src="src"
                            :alt="`New image ${idx + 1}`"
                            class="w-full h-24 rounded-lg object-cover border border-gray-200"
                        />
                        <button
                            type="button"
                            @click="removeFile(idx)"
                            class="absolute top-1 right-1 p-1 bg-red-100 hover:bg-red-200 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-200"
                        >
                            <XIcon class="h-3 w-3 text-red-600" />
                        </button>
                        <div class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-green-500/80 rounded text-white text-xs">
                            New {{ idx + 1 }}
                        </div>
                    </div>
                </div>
            </div>


            <div v-else class="text-center py-8 text-gray-500">
                <p>No images to display</p>
                <p class="text-xs mt-1">Gallery items: {{ galleryItems.length }}, New uploads: {{ files.length }}</p>
            </div>


            <div v-if="form.errors.gallery_images || form.errors.gallery || galleryErrors.length" class="space-y-1">
                <p v-if="form.errors.gallery_images" class="text-sm text-red-600 flex items-center space-x-1">
                    <XIcon class="h-4 w-4" />
                    <span>{{ form.errors.gallery_images }}</span>
                </p>
                <p v-if="form.errors.gallery" class="text-sm text-red-600 flex items-center space-x-1">
                    <XIcon class="h-4 w-4" />
                    <span>{{ form.errors.gallery }}</span>
                </p>
                <p
                    v-for="(msg, i) in galleryErrors"
                    :key="i"
                    class="text-sm text-red-600 flex items-center space-x-1"
                >
                    <XIcon class="h-4 w-4" />
                    <span>{{ msg }}</span>
                </p>
            </div>
        </div>
    </div>
</template>
