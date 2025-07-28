<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { Upload, XIcon, Image } from 'lucide-vue-next';

interface Props {
    form: any;
    featuredPreview?: string | null;
    modelName?: string
    title?: string;
}

const props = withDefaults(defineProps<Props>(), {
    featuredPreview: null,
    modelName: 'featured'
});


const file = defineModel<File | null>('file', { default: null });

const preview = ref<string | null>(null);

const resetFeature = () => {
    if (preview.value && preview.value.startsWith('blob:')) {
        URL.revokeObjectURL(preview.value);
    }

    file.value = null;
    preview.value = props.featuredPreview;
};

defineExpose({
    resetFeature
});

watch(() => props.featuredPreview, (newPreview) => {
    if (newPreview && !file.value) {
        preview.value = newPreview;
    }
}, { immediate: true });


watch(file, (newFile) => {
    if (newFile) {
        if (preview.value && preview.value.startsWith('blob:')) {
            URL.revokeObjectURL(preview.value);
        }
        preview.value = URL.createObjectURL(newFile);
    }
}, { immediate: true });

const onFileChange = (e: Event) => {
    const input = e.target as HTMLInputElement;
    const selectedFile = input.files?.[0] || null;

    if (preview.value && preview.value.startsWith('blob:')) {
        URL.revokeObjectURL(preview.value);
    }


    file.value = selectedFile;


    input.value = '';
};

const removeImage = () => {

    if (preview.value && preview.value.startsWith('blob:')) {
        URL.revokeObjectURL(preview.value);
    }


    file.value = null;
    preview.value = props.featuredPreview;
};


onMounted(() => {
    return () => {
        if (preview.value && preview.value.startsWith('blob:')) {
            URL.revokeObjectURL(preview.value);
        }
    };
});
</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-primary rounded-lg">
                <Image class="h-5 w-5 text-white" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900">{{props.title || 'Featured Image' }}</h3>
        </div>

        <div class="relative">
            <div class="relative rounded-lg border-2 border-dashed border-gray-300 p-6 transition-all duration-200 hover:border-primary hover:bg-blue-50/50 group">
                <input
                    type="file"
                    accept="image/*"
                    @change="onFileChange"
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
                    <img
                        :src="preview"
                        :alt="file ? file.name : 'Featured image'"
                        class="w-full h-40 rounded-lg object-cover"
                        @error="console.log('Image failed to load:', preview)"
                        @load="console.log('Image loaded successfully:', preview)"
                    />
                    <button
                        type="button"
                        @click="removeImage"
                        class="absolute top-2 right-2 p-1 bg-red-100 hover:bg-red-200 rounded-full shadow-lg transition-colors duration-200"
                    >
                        <XIcon class="h-4 w-4 text-red-600" />
                    </button>
                    <div class="absolute bottom-2 left-2 px-2 py-1 bg-black/50 rounded text-white text-xs">
                        {{ file ? 'New' : 'Current' }}
                    </div>
                </div>
            </div>


            <p v-if="form.errors[modelName]" class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                <XIcon class="h-4 w-4" />
                <span>{{ form.errors[modelName] }}</span>
            </p>
        </div>
    </div>
</template>
