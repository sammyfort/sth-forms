

<script setup lang="ts">
import {ref} from 'vue'
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Building2 } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';

import FormComponent from '@/components/FormComponent.vue';
import InputSelect from '@/components/InputSelect.vue';
import InputText from '@/components/InputText.vue';
import FeatureFileUpload from '@/components/FeatureFileUpload.vue';
import GalleryFilesUpload from '@/components/GalleryFilesUpload.vue';

const props = defineProps<{
    categories: Array<{ label: string, value: string }>,
    regions: Array<{ label: string, value: string }>
}>();
const galleryUploadRef = ref();
const featureUploadRef = ref();
const form = useForm({
    title: '',
    description: '',
    first_mobile: '',
    business_name: '',
    second_mobile: '',
    email: '',
    address: '',
    town: '',
    gps: '',
    region_id: '',
    categories: [],
    featured: null,
    gallery: []
});

const createService = () => {
    form.post(route('my-services.store'), {
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess(res.props.message);

            } else {
                toastError(res.props.message);
            }
            form.reset();
            if (featureUploadRef.value) {
                featureUploadRef.value.resetFeature();
            }
            if (galleryUploadRef.value) {
                galleryUploadRef.value.resetGallery();
            }
        }
    });
};


</script>

<template>
    <Head title="Create Service" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Add New Service"
                subtitle="Create a new service listing for your business"
                :icon="Building2"
            />

            <FormComponent :form="form" submit-text="Create Service"
                           processing-text="Creating Service..." @submit="createService">

                <template #form-sections>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Business Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <InputSelect
                                label="Select Region" :form="form" model="region_id" :options="props.regions"   required searchable
                            />
                            <InputText :form="form" label="Name/Title" model="title" required />
                            <InputText :form="form" label="Town" model="town" required />
                            <InputText :form="form" label="Address" model="address" required />
                            <InputText :form="form" label="First Mobile No" type=tel model="first_mobile" required />
                            <InputText :form="form" label="Second Mobile No" type="tel" model="second_mobile"  />
                            <InputText :form="form" label="Email address" type="email" model="email" required />
                            <InputText :form="form" label="Business Name" model="business_name"  />
                            <InputText :form="form" label="GPS Address" model="gps" required />
                            <InputText :form="form" label="Description" model="description"  textarea />
                            <div class="md:col-span-2">
                                <InputSelect label="Fields Of Service" :form="form" model="categories" :options="props.categories" taggable required searchable />
                            </div>

                        </div>
                    </div>
                </template>

                <template #media-section>
                    <FeatureFileUpload
                        ref="featureUploadRef"
                        :form="form"
                        v-model:file="form.featured"
                        model-name="featured"
                    />

                    <GalleryFilesUpload
                        ref="galleryUploadRef"
                        :form="form"
                        v-model:files="form.gallery"
                    />
                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
