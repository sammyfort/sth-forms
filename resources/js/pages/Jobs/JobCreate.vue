

<script setup lang="ts">
import {ref} from 'vue'
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Building2 } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';
import TextEditor from '@/components/forms/TextEditor.vue'
import FormComponent from '@/components/FormComponent.vue';
import InputText from '@/components/InputText.vue';
import FeatureFileUpload from '@/components/FeatureFileUpload.vue';
import GalleryFilesUpload from '@/components/GalleryFilesUpload.vue';
import InputSelect from '@/components/InputSelect.vue';
import InputError from '@/components/InputError.vue';

const props = defineProps<{
    types: Array<{ label: string; value: string }>
    statuses: Array<{ label: string; value: string }>
}>();
const galleryUploadRef = ref();
const featureUploadRef = ref();
const form = useForm({
    title: '',
    type: '',
    status: '',
    category_id: '',
    summary: '',
    description: '',
    contact_name: '',
    contact_phone: '',
    contact_email: '',
    contact_website: '',
    expires_at: '',
    featured: null,
    gallery: []
});

const createJob = () => {
    form.post(route('my-jobs.store'), {
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess(res.props.message);

            } else {
                toastError(res.props.message);
            }
            form.reset();

        }
    });
};


</script>

<template>
    <Head title="Create Job" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Add New Job"
                subtitle="Create a new Job"
                :icon="Building2"
            />

            <FormComponent :form="form" submit-text="Create Job"
                           processing-text="Creating Service..." @submit="createJob">

                <template #form-sections>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Job Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <InputText :form="form" label="Title" model="title" required />
                            <InputSelect label="Job Type" :form="form" model="type" :options="props.types" required searchable />
                            <InputSelect label="Job Status" :form="form" model="status" :options="props.statuses" required searchable />
                            <InputText :form="form" label="Ends At" type="date" model="expires_at" required />
                            <InputText :form="form" label="Contact Name" type=tel model="contact_name" required />
                            <InputText :form="form" label="Contact No" type="tel" model="contact_phone"  />
                            <InputText :form="form" label="Contact Email" type="email" model="contact_email" required />
                            <InputText :form="form" label="Website" model="contact_website"  />

                            <InputText :form="form" label="Category" model="category"   />
                            <InputText :form="form" label="Summary" model="summary" required textarea />
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Job Description</label>
                        <TextEditor v-model="form.description" />
                        <InputError v-if="form.errors.description" :message="form.errors.description " />

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
