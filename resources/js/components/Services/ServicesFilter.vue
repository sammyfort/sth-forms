<script setup lang="ts">
import InputText from '@/components/InputText.vue';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectTrigger, SelectValue } from '@/components/ui/select';
import { RegionI, ServiceCategoryI } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import { useFilterQuery } from '@/lib/useFilterQuery';
import { onBeforeMount } from 'vue';
import CountryRegionSelector from '@/components/CountryRegionSelector.vue';

type Props = {
    categories: ServiceCategoryI[];
    regions: RegionI[];
};

const props = defineProps<Props>();

const filterForm = useForm<{
    q: string | null;
    categories: Array<number> | null;
    country: number | null;
    region: number | null;
}>({
    q: '',
    categories: null,
    country: null,
    region: null,
});

const { filteredQuery, runFilter } = useFilterQuery({
    form: filterForm,
    routeName: 'services.index',
    preserveKeys: ['servicesData'],
    buildQuery: (qBuilder) => {
        if (filterForm.q) qBuilder.filter('q', filterForm.q);
        if (filterForm.region) qBuilder.filter('region', filterForm.region.toString());
        if (filterForm.country) qBuilder.filter('country', filterForm.country.toString());
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
    const countryParam = urlParams.get('filter[country]');
    const regionParam = urlParams.get('filter[region]');

    filterForm.categories = categoryParam ? categoryParam.split(',').map((cat) => Number(cat)) : [];
    filterForm.country = countryParam ? Number(countryParam) : null;
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
            placeholder="Search service providers..."
            container-class="gap-0 col-span-2 md:col-span-1 lg:col-span-2"
            type="text"
            model="q"
            :form="filterForm"
        />
        <Select v-model="filterForm.categories" :default-value="filterForm.categories" :multiple="true">
            <SelectTrigger class="w-full col-span-2 md:col-span-1 lg:col-span-2">
                <SelectValue placeholder="Type of artisan" />
            </SelectTrigger>
            <SelectContent
                :options="props.categories.map( (cat)=>{return {label: cat.name, value: cat.id as unknown as string}})"
            />
        </Select>

        <CountryRegionSelector
            :form="filterForm"
            region-model="region"
            country-model="country"
        />

        <div class="grid md:grid-cols-2 lg:grid-cols-1 col-span-2 md:col-span-1 lg:col-span-2 gap-3">
            <Button :processing="filterForm.processing" v-show="filterForm.country || filterForm.region || filterForm.categories?.length || filterForm.q?.length" @click="runFilter">Apply Filter </Button>
            <Button v-show="filteredQuery" variant="secondary" @click="router.visit(route('services.index'))">Reset </Button>
        </div>
    </div>
</template>

<style scoped></style>
