<script setup lang="ts">
import InputText from '@/components/InputText.vue';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { JobCategoryI, RegionI } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import { useFilterQuery } from '@/lib/useFilterQuery';
import { onBeforeMount } from 'vue';
import CountryRegionSelector from '@/components/CountryRegionSelector.vue';

type Props = {
    categories: JobCategoryI[];
    regions: RegionI[];
};

defineProps<Props>();

const filterForm = useForm<{
    q: string | null;
    categories: number[] | null;
    region: number | null;
    country: number | null;
    work_mode: string[] | null
    job_type: string[] | null
}>({
    q: '',
    categories: null,
    region: null,
    country: null,
    work_mode: null,
    job_type: null,
});

const workModes = ['onsite', 'remote', 'hybrid'];
const jobTypes = ['Full time', 'Part time', 'Contract', 'Internship'];

const { filteredQuery, runFilter } = useFilterQuery({
    form: filterForm,
    routeName: 'jobs.index',
    preserveKeys: ['jobsData'],
    buildQuery: (qBuilder) => {
        if (filterForm.q) qBuilder.filter('q', filterForm.q);
        if (filterForm.region) qBuilder.filter('region', filterForm.region.toString());
        if (filterForm.country) qBuilder.filter('country', filterForm.country.toString());
        if (filterForm.categories?.length) {
            filterForm.categories.forEach((cat) => {
                qBuilder.filter('categories', cat.toString());
            });
        }
        if (filterForm.work_mode?.length) {
            filterForm.work_mode.forEach((cat) => {
                qBuilder.filter('work_mode', cat.toString());
            });
        }
        if (filterForm.job_type?.length) {
            filterForm.job_type.forEach((cat) => {
                qBuilder.filter('job_type', cat.toString());
            });
        }
    },
});

onBeforeMount(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const qParam = urlParams.get('filter[q]');
    const categoryParam = urlParams.get('filter[categories]');
    const workModeParam = urlParams.get('filter[work_mode]');
    const jobTypeParam = urlParams.get('filter[job_type]');
    const regionParam = urlParams.get('filter[region]');
    const countryParam = urlParams.get('filter[country]');

    filterForm.categories = categoryParam ? categoryParam.split(',').map((cat) => Number(cat)) : [];
    filterForm.work_mode = workModeParam ? workModeParam.split(',').map((wm) => wm) : [];
    filterForm.job_type = jobTypeParam ? jobTypeParam.split(',').map((wm) => wm) : [];
    filterForm.region = regionParam ? Number(regionParam) : null;
    filterForm.country = countryParam ? Number(countryParam) : null;
    filterForm.q = qParam ? (qParam as string) : '';

    if (urlParams.size > 0) {
        filteredQuery.value = '&' + urlParams.toString();
    }
});
</script>

<template>
    <div class="mb-3 text-lg font-semibold">Filter</div>
    <div class="grid grid-cols-2 gap-4">
        <InputText
            placeholder="Search jobs..."
            container-class="gap-0 col-span-2 md:col-span-1 lg:col-span-2"
            type="text"
            model="q"
            :form="filterForm"
        />
        <Select v-model="filterForm.job_type" :default-value="filterForm.job_type" :multiple="true">
            <SelectTrigger class="w-full col-span-2 md:col-span-1 lg:col-span-2">
                <SelectValue placeholder="Type" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem v-for="jopType in jobTypes" :key="jopType" :value="jopType">
                    {{ jopType }}
                </SelectItem>
            </SelectContent>
        </Select>
        <Select v-model="filterForm.work_mode" :default-value="filterForm.work_mode" :multiple="true">
            <SelectTrigger class="w-full col-span-2 md:col-span-1 lg:col-span-2">
                <SelectValue placeholder="Work mode" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem v-for="workMode in workModes" :key="workMode" :value="workMode">
                    {{ workMode }}
                </SelectItem>
            </SelectContent>
        </Select>
        <Select v-model="filterForm.categories" :default-value="filterForm.categories" :multiple="true">
            <SelectTrigger class="w-full col-span-2 md:col-span-1 lg:col-span-2">
                <SelectValue placeholder="Job category" />
            </SelectTrigger>
            <SelectContent
                :options="categories.map( (cat)=>{return {label: cat.name, value: cat.id as unknown as string}})"
            />
        </Select>
        <CountryRegionSelector
            :form="filterForm"
            region-model="region"
            country-model="country"
        />
        <div class="grid md:grid-cols-2 lg:grid-cols-1 col-span-2 md:col-span-1 lg:col-span-2 gap-3">
            <Button
                v-show="filterForm.region || filterForm.country || filterForm.categories?.length || filterForm.q?.length || filterForm.work_mode?.length || filterForm.job_type?.length"
                @click="runFilter"
                :processing="filterForm.processing"
            >Apply Filter</Button>
            <Button v-show="filteredQuery" variant="secondary" @click="router.visit(route('jobs.index'))">Reset </Button>
        </div>
    </div>
</template>

<style scoped></style>
