<script setup lang="ts">
import InputText from '@/components/InputText.vue';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectTrigger, SelectValue } from '@/components/ui/select';
import { RegionI, SignboardCategoryI } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import { useFilterQuery } from '@/lib/useFilterQuery';
import { onBeforeMount } from 'vue';

type Props = {
    categories: SignboardCategoryI[];
    regions: RegionI[];
};

defineProps<Props>();

const filterForm = useForm<{
    q: string | null;
    categories: Array<number> | null;
    region: number | null;
}>({
    q: '',
    categories: null,
    region: null,
});

const { filteredQuery, runFilter } = useFilterQuery({
    form: filterForm,
    routeName: 'signboards.index',
    preserveKeys: ['signboardsData'],
    buildQuery: (qBuilder) => {
        if (filterForm.q) qBuilder.filter('q', filterForm.q);
        if (filterForm.region) qBuilder.filter('region', filterForm.region.toString());
        if (filterForm.categories?.length) {
            filterForm.categories.forEach((cat) => {
                qBuilder.filter('categories', cat.toString());
            });
        }
    },
});

onBeforeMount(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const qParam = urlParams.get('filter[q]');
    const categoryParam = urlParams.get('filter[categories]');
    const regionParam = urlParams.get('filter[region]');
    filterForm.categories = categoryParam ? categoryParam.split(',').map((cat) => Number(cat)) : [];
    filterForm.region = regionParam ? Number(regionParam) : null;
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
            placeholder="Search signboard..."
            container-class="gap-0 col-span-2 md:col-span-1 lg:col-span-2"
            type="text"
            model="q"
            :form="filterForm"
        />
        <Select v-model="filterForm.categories" :default-value="filterForm.categories" :multiple="true">
            <SelectTrigger class="w-full col-span-2 md:col-span-1 lg:col-span-2">
                <SelectValue placeholder="Signboard category" />
            </SelectTrigger>
            <SelectContent
                :options="categories.map( (cat)=>{return {label: cat.name, value: cat.id as unknown as string}})"
            />
        </Select>
        <Select v-model="filterForm.region" :default-value="filterForm.region">
            <SelectTrigger class="w-full col-span-2 md:col-span-1 lg:col-span-2">
                <SelectValue placeholder="Region" />
            </SelectTrigger>
            <SelectContent
                :options="regions.map( (reg)=>{return {label: reg.name, value: reg.id as unknown as string}})"
            />
        </Select>
        <div class="grid md:grid-cols-2 lg:grid-cols-1 col-span-2 md:col-span-1 lg:col-span-2 gap-3">
            <Button v-show="filterForm.region || filterForm.categories?.length || filterForm.q?.length" @click="runFilter">Apply Filter </Button>
            <Button v-show="filteredQuery" variant="secondary" @click="router.visit(route('services.index'))">Reset </Button>
        </div>
    </div>
</template>

<style scoped></style>
