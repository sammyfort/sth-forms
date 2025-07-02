<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { LoaderCircle, Building2, MapPin, Edit3, Upload, XIcon, Plus, Image, Check } from 'lucide-vue-next';
import InputText from '@/components/InputText.vue';
import InputSelect from '@/components/InputSelect.vue';
import { Button } from '@/components/ui/button';
import Layout from '@/layouts/Layout.vue';
import { getApi } from '@/lib/meta';

const props = defineProps<{
    signboard: {
        id: number;
        business_id: number;
        region_id: number;
        slug: string;
        town: string;
        street: string;
        landmark: string;
        blk_number: string;
        gps: string;
        featured_url?: string;
        gallery_urls: string[];
    };
}>();

const form = useForm({
    business_id: '',
    region_id: '',
    town: '',
    street: '',
    landmark: '',
    blk_number: '',
    gps: '',
    featured_image: null,
    gallery_images: [] as File[],
    removed_gallery_urls: [] as string[],
});

const featuredPreview = ref<string | null>(props.signboard.featured_url || null);
const galleryPreviews = ref<string[]>([...props.signboard.gallery_urls || []]);

const galleryItems = ref<Array<{url: string, isOriginal: boolean, originalIndex?: number}>>([]);

const galleryErrors = computed(() =>
    Object.keys(form.errors)
        .filter((key) => key.startsWith('gallery_images.'))
        .map((key) => form.errors[key])
);

const regions = ref([]);
const businesses = ref([]);

onMounted(async () => {
    form.business_id = String(props.signboard.business_id);
    form.region_id = String(props.signboard.region_id);
    form.town = props.signboard.town || '';
    form.street = props.signboard.street || '';
    form.landmark = props.signboard.landmark || '';
    form.blk_number = props.signboard.blk_number || '';
    form.gps = props.signboard.gps || '';

    galleryItems.value = (props.signboard.gallery_urls || []).map((url, index) => ({
        url,
        isOriginal: true,
        originalIndex: index
    }));

    const [regRes, busRes] = await Promise.all([
        getApi('regions'),
        getApi('authBusinesses')
    ]);
    regions.value = regRes.metadata?.regions ?? [];
    businesses.value = busRes.metadata?.businesses ?? [];
});

const handleFeaturedImage = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0] || null;
    form.featured_image = file;
    if (featuredPreview.value && featuredPreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(featuredPreview.value);
    }
    featuredPreview.value = file ? URL.createObjectURL(file) : null;
};

const removeFeatured = () => {
    form.featured_image = null;
    if (featuredPreview.value && featuredPreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(featuredPreview.value);
    }
    featuredPreview.value = null;
};

const handleGalleryImages = (e: Event) => {
    const files = (e.target as HTMLInputElement).files ? Array.from((e.target as HTMLInputElement).files!) : [];

    files.forEach(file => {
        const blobUrl = URL.createObjectURL(file);
        form.gallery_images.push(file);

        galleryItems.value.push({
            url: blobUrl,
            isOriginal: false
        });
    });
    galleryPreviews.value = galleryItems.value.map(item => item.url);
};

const removeGalleryImage = (idx: number) => {
    const item = galleryItems.value[idx];

    if (item.isOriginal) {
        form.removed_gallery_urls.push(item.url);
    } else {
        const blobUrl = item.url;
        URL.revokeObjectURL(blobUrl);

        const fileIndex = galleryItems.value
            .slice(0, idx)
            .filter(i => !i.isOriginal).length;

        if (fileIndex < form.gallery_images.length) {
            form.gallery_images.splice(fileIndex, 1);
        }
    }

    galleryItems.value.splice(idx, 1);
    galleryPreviews.value = galleryItems.value.map(item => item.url);

};

const updateSignboard = () => {
   // console.log(form)
    form.post(route('my-signboards.update', props.signboard.id), {
        forceFormData: true,

        onSuccess: res => {
            const message = res.props.message;
            if (res.props.success) {
                toastSuccess(message);
                form.removed_gallery_urls = [];
            } else {
                toastError(message);
            }
        },
        onError: errors => {
            //console.log('Validation errors:', errors);
        },
        preserveScroll: true,

    });
};
</script>
<template>
    <Head title="Edit Signboard" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <div class="bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary rounded-lg">
                            <Edit3 class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Edit Signboard</h1>
                            <p class="text-sm text-gray-600 mt-1">Update your signboard listing information</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-2 space-y-6">

                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-primary rounded-lg">
                                    <Building2 class="h-5 w-5 text-white" />
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">Business Information</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <InputSelect label="Select Business" :form="form" model="business_id" :options="businesses" required searchable />
                                </div>
                                <InputSelect label="Region" :form="form" model="region_id" :options="regions" required searchable />
                                <InputText :form="form" label="Town" model="town" required />
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
                                <InputText :form="form" label="Street Address" model="street" placeholder="e.g., Main Street" />
                                <InputText :form="form" label="Landmark" model="landmark" required placeholder="e.g., Near Central Mall" />
                                <InputText :form="form" label="Block Number" model="blk_number" placeholder="e.g., Block A" />
                                <InputText :form="form" label="GPS Coordinates" model="gps" placeholder="e.g., 5.6037, -0.1870" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">

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
                                        @change="handleFeaturedImage"
                                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                    />

                                    <div v-if="!featuredPreview" class="text-center">
                                        <Upload class="mx-auto h-12 w-12 text-gray-400 group-hover:text-primary transition-colors duration-200" />
                                        <div class="mt-4">
                                            <p class="text-sm font-medium text-gray-900">Upload featured image</p>
                                            <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 10MB</p>
                                        </div>
                                    </div>

                                    <div v-else class="relative">
                                        <img :src="featuredPreview" class="w-full h-40 rounded-lg object-cover" />
                                        <button
                                            type="button"
                                            @click="removeFeatured"
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

                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-primary rounded-lg">
                                        <Plus class="h-5 w-5 text-white" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Gallery Images</h3>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                    {{ galleryPreviews.length }} uploaded
                                </span>
                            </div>

                            <div class="space-y-4">
                                <div class="relative rounded-lg border-2 border-dashed border-gray-300 p-4 transition-all duration-200 hover:border-primary hover:bg-indigo-50/50 group">
                                    <input
                                        type="file"
                                        multiple
                                        accept="image/*"
                                        @change="handleGalleryImages"
                                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                    />

                                    <div class="text-center">
                                        <Plus class="mx-auto h-8 w-8 text-gray-400 group-hover:text-primary transition-colors duration-200" />
                                        <p class="text-sm font-medium text-gray-700 mt-2">Add more images</p>
                                        <p class="text-xs text-gray-500">Multiple selection supported</p>
                                    </div>
                                </div>

                                <div v-if="galleryPreviews.length" class="grid grid-cols-2 gap-3">
                                    <div
                                        v-for="(src, idx) in galleryPreviews"
                                        :key="idx"
                                        class="relative group"
                                    >
                                        <img :src="src" class="w-full h-24 rounded-lg object-cover border border-gray-200" />
                                        <button
                                            type="button"
                                            @click="removeGalleryImage(idx)"
                                            class="absolute top-1 right-1 p-1 bg-red-100 hover:bg-red-200 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-200"
                                        >
                                            <XIcon class="h-3 w-3 text-red-600" />
                                        </button>
                                        <div class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-black/50 rounded text-white text-xs">
                                            {{ idx + 1 }}
                                        </div>
                                    </div>
                                </div>

                                <div v-if="form.errors.gallery_images || galleryErrors.length" class="space-y-1">
                                    <p v-if="form.errors.gallery_images" class="text-sm text-red-600 flex items-center space-x-1">
                                        <XIcon class="h-4 w-4" />
                                        <span>{{ form.errors.gallery_images }}</span>
                                    </p>
                                    <p v-for="(msg, i) in galleryErrors" :key="i" class="text-sm text-red-600 flex items-center space-x-1">
                                        <XIcon class="h-4 w-4" />
                                        <span>{{ msg }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">These information will appear to the public.</h3>
                                <p class="text-sm text-gray-600 mt-1">Review your information and submit when ready</p>
                            </div>
                            <Button
                                :disabled="form.processing"
                                @click="updateSignboard"
                                class="px-8 py-3 bg-gradient-to-r from-primary to-primary hover:from-primary hover:to-orange-300 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                                <Check v-else class="mr-2 h-5 w-5" />
                                {{ form.processing ? 'Updating Signboard...' : 'Update Signboard' }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
