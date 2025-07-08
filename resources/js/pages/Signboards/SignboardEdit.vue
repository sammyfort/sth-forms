<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Edit3 } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import SignboardForm from '@/pages/Signboards/blocks/SignboardForm.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';

const props = defineProps<{
    signboard: {
        id: number;
        business_id: number;
        region_id: number;
        categories?: Array<{ id: number; name: string }>;
        slug: string;
        town: string;
        street: string;
        landmark: string;
        blk_number: string;
        gps: string;
        featured_url?: string;
        gallery_urls: string[];
    };
    categories: Array<{ label: string, value: string }>,
    regions: Array<{ label: string, value: string }>
    businesses: Array<{ label: string, value: string }>
}>();

const form = useForm({
    business_id: '',
    region_id: '',
    categories: [],
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

onMounted(() => {
    form.business_id = String(props.signboard.business_id);
    form.region_id = String(props.signboard.region_id);
    form.categories = props.signboard.categories?.map(cat => cat.id) || [];
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
});

const updateSignboard = () => {
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
        preserveScroll: true,
    });
};

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
    (e.target as HTMLInputElement).value = '';
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
</script>

<template>
    <Head title="Edit Signboard" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Edit Signboard"
                subtitle="Update your signboard listing information"
                :icon="Edit3"
            />

            <SignboardForm
                :form="form"
                :categories="categories"
                :regions="regions"
                :businesses="businesses"
                :business-field-disabled="true"
                :featured-preview="featuredPreview"
                :gallery-previews="galleryPreviews"
                :gallery-items="galleryItems"
                :gallery-errors="galleryErrors"
                submit-text="Update Signboard"
                processing-text="Updating Signboard..."
                @handle-featured-image="handleFeaturedImage"
                @remove-featured="removeFeatured"
                @handle-gallery-images="handleGalleryImages"
                @remove-gallery-image="removeGalleryImage"
                @submit="updateSignboard"
            />
        </div>
    </Layout>
</template>
