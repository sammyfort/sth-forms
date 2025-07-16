<script setup lang="ts">
import { ref, watch, computed, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Building2 } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import SignboardForm from '@/pages/Signboards/blocks/SignboardForm.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';

const props = defineProps<{
    business?: Number;
    categories: Array<{ label: string, value: string }>,
    regions: Array<{ label: string, value: string }>
    businesses: Array<{ label: string, value: string }>
}>();

const form = useForm({
    business_id: '',
    region_id: '',
    categories: [],
    name: '',
    town: '',
    street: '',
    landmark: '',
    blk_number: '',
    gps: '',
    featured_image: null,
    gallery_images: []
});

const businessFieldDisabled = ref(false);
const featuredPreview = ref<string | null>(null);
const galleryPreviews = ref<string[]>([]);

const galleryErrors = computed(() =>
    Object.keys(form.errors)
        .filter((key) => key.startsWith('gallery_images.'))
        .map((key) => form.errors[key])
);

onMounted(() => {
    if (props.business) {
        form.business_id = String(props.business);
        businessFieldDisabled.value = true;
    }
});


const createSignboard = () => {
    form.post(route('my-signboards.store'), {
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess(res.props.message);

            } else {
                toastError(res.props.message);
            }
            form.reset();
            featuredPreview.value = null;
            galleryPreviews.value = [];
        }
    });
};

const handleFeaturedImage = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0] || null;
    form.featured_image = file;
    featuredPreview.value = file ? URL.createObjectURL(file) : null;
};

const removeFeatured = () => {
    form.featured_image = null;
    if (featuredPreview.value) URL.revokeObjectURL(featuredPreview.value);
    featuredPreview.value = null;
};

const handleGalleryImages = (e: Event) => {
    const files = (e.target as HTMLInputElement).files ? Array.from((e.target as HTMLInputElement).files!) : [];
    files.forEach((file) => {
        form.gallery_images.push(file);
        galleryPreviews.value.push(URL.createObjectURL(file));
    });
    (e.target as HTMLInputElement).value = '';
};

const removeGalleryImage = (idx: number) => {
    const url = galleryPreviews.value.splice(idx, 1)[0];
    form.gallery_images.splice(idx, 1);
    URL.revokeObjectURL(url);
};

watch(featuredPreview, (v, o) => o && URL.revokeObjectURL(o));
watch(galleryPreviews, (v, o) => o.forEach((u) => URL.revokeObjectURL(u)));
</script>

<template>
    <Head title="Create Signboard" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Add New Signboard"
                subtitle="Create a new signboard listing for your business"
                :icon="Building2"
            />

            <SignboardForm
                :form="form"
                :categories="categories"
                :regions="regions"
                :businesses="businesses"
                :business-field-disabled="businessFieldDisabled"
                :featured-preview="featuredPreview"
                :gallery-previews="galleryPreviews"
                :gallery-errors="galleryErrors"
                submit-text="Create Signboard"
                processing-text="Creating Signboard..."
                @handle-featured-image="handleFeaturedImage"
                @remove-featured="removeFeatured"
                @handle-gallery-images="handleGalleryImages"
                @remove-gallery-image="removeGalleryImage"
                @submit="createSignboard"
            />
        </div>
    </Layout>
</template>
