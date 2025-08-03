<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Building2 } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';
import TextEditor from '@/components/forms/TextEditor.vue';
import FormComponent from '@/components/FormComponent.vue';
import InputText from '@/components/InputText.vue';
import FeatureFileUpload from '@/components/FeatureFileUpload.vue';
import InputSelect from '@/components/InputSelect.vue';
import InputError from '@/components/InputError.vue';
import { JobI } from '@/types';

const props = defineProps<{
    job: JobI
    types: Array<{ label: string; value: string }>
    statuses: Array<{ label: string; value: string }>
    modes: Array<{ label: string; value: string }>
    regions: Array<{ label: string; value: string }>
    categories: Array<{ label: string; value: string }>
}>();

const featureUploadRef = ref();

const form = useForm({
    company_name: '',
    title: '',
    categories: [],
    job_type: '',
    work_mode: '',
    status: '',
    deadline: '',
    region_id: '',
    town: '',
    salary: '',
    summary: '',
    description: '',
    how_to_apply: '',
    application_link: '',
    featured: null
});

onMounted(() => {
    const job = props.job;

    form.company_name = job.company_name || '';
    form.title = job.title || '';
    form.categories = (job.categories || []).map((cat: any) => cat.id);
    form.job_type = job.job_type || '';
    form.work_mode = job.work_mode || '';
    form.status = job.status || '';
    form.deadline = job.deadline ? new Date(job.deadline).toISOString().split('T')[0] : '';
    form.region_id = job.region_id || '';
    form.town = job.town || '';
    form.salary = job.salary || '';
    form.summary = job.summary || '';

    form.how_to_apply = job.how_to_apply || '';
    form.application_link = job.application_link || '';
    form.description = job.description || '';


});



const updateJob = () => {
    form.put(route('my-jobs.update', props.job.id), {
        preserveScroll: true,
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess(res.props.message);
            } else {
                toastError(res.props.message);
            }
        }
    });
};
</script>


<template>
    <Head title="Update Job" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Edit Job"
                subtitle="Update job details"
                :icon="Building2"
            />

            <FormComponent
                :form="form"
                submit-text="Update Job"
                processing-text="Updating..."
                @submit="updateJob"
                container-width="max-w-6xl"
            >
                <template #form-sections>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Job Information</h2>
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-6">
                            <InputText :form="form" label="Company Name" model="company_name" required />
                            <InputSelect :form="form" label="Job Sector" model="categories" :options="props.categories"
                                         taggable required searchable />
                            <InputText :form="form" label="Title" model="title" required />
                            <InputSelect :form="form" label="Job Type" model="job_type" :options="props.types"
                                         required />
                            <InputSelect :form="form" label="Work Mode" model="work_mode" :options="props.modes"
                                         required />
                            <InputSelect :form="form" label="Region" model="region_id" :options="props.regions" required
                                         searchable />
                            <InputText :form="form" label="Town" model="town" required />
                            <InputSelect label="Job Status" :form="form" model="status" :options="props.statuses"
                                         required />
                            <InputText :form="form" label="Salary" model="salary" />
                            <InputText :form="form" label="How to apply" model="how_to_apply" required />
                            <InputText :form="form" label="Application Link" model="application_link" required />

                            <InputText :form="form" label="Summary" model="summary" required textarea />
                            <InputText :form="form" label="Deadline" model="deadline" type="date" required />
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
                        title="Company Logo"
                        :featured-preview="props.job.featured"
                    />
                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
