<script setup lang="ts">
import InputText from '@/components/InputText.vue';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ProductCategoryI, RegionI } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import { useFilterQuery } from '@/lib/useFilterQuery';
import { onBeforeMount } from 'vue';
import { Slider } from '@/components/ui/slider';
import { cediSign } from '@/lib/helpers';

type Props = {
    categories: ProductCategoryI[]
    regions: RegionI[]
    highestPrice: number
};


const props = defineProps<Props>();

const filterForm = useForm<{
    q: string | null
    categories: Array<number> | null
    region: number | null
    price: string
}>({
    q: '',
    categories: null,
    region: null,
    price: `0,${props.highestPrice}`,
});

const { filteredQuery, runFilter } = useFilterQuery({
    form: filterForm,
    routeName: 'products.index',
    preserveKeys: ['productsData'],
    buildQuery: (qBuilder) => {
        if (filterForm.q) qBuilder.filter('q', filterForm.q);
        if (filterForm.region) qBuilder.filter('region', filterForm.region.toString());
        if (filterForm.price) qBuilder.filter('price', filterForm.price);
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
    const priceParam = urlParams.get('filter[price]');

    filterForm.categories = categoryParam ? categoryParam.split(',').map((cat) => Number(cat)) : [];
    filterForm.region = regionParam ? Number(regionParam) : null;
    filterForm.q = qParam ? (qParam as string) : '';
    filterForm.price = priceParam ? (priceParam as string) : filterForm.price;

    if (urlParams.size > 0) {
        filteredQuery.value = '&' + urlParams.toString();
    }
});
</script>

<template>
    <div class="mb-3 text-lg font-semibold">Filter</div>
    <div class="grid grid-cols-2 gap-4">
        <InputText
            placeholder="Search products..."
            container-class="gap-0 col-span-2 md:col-span-1 lg:col-span-2"
            type="text"
            model="q"
            :form="filterForm"
        />
        <Select v-model="filterForm.categories" :default-value="filterForm.categories" :multiple="true">
            <SelectTrigger class="w-full col-span-2 md:col-span-1 lg:col-span-2">
                <SelectValue placeholder="Category" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                </SelectItem>
            </SelectContent>
        </Select>
        <Select v-model="filterForm.region" :default-value="filterForm.region">
            <SelectTrigger class="w-full col-span-2 md:col-span-1 lg:col-span-2">
                <SelectValue placeholder="Region" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem v-for="region in regions" :key="region.id" :value="region.id">
                        {{ region.name }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
        <div class="flex flex-col gap-3 w-full col-span-2 md:col-span-1 lg:col-span-2">
            <div class="w-full">Price</div>
            <Slider
                :default-value="[filterForm.price.split(',')[0] as unknown as number, filterForm.price.split(',')[1] as unknown as number ]"
                :max="highestPrice as number ?? 100"
                :step="1"
                @update:model-value="(value) => {filterForm.price = `${value?.[0]},${value?.[1]}`}"
                class="bg-gray-300"
            />
            <div class="flex justify-between">
                <span> <span class="text-xs">{{ cediSign() }}</span>{{ filterForm.price?.length ? filterForm.price.split(',')[0] : 0 }}</span>
                <span><span class="text-xs">{{ cediSign() }}</span>{{ filterForm.price?.length ? filterForm.price.split(',')[1] : 100 }}</span>
            </div>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-1 col-span-2 md:col-span-1 lg:col-span-2 gap-3">
            <Button @click="runFilter" :processing="filterForm.processing">Apply Filter </Button>
            <Button v-show="filteredQuery" variant="secondary" @click="router.visit(route('products.index'))">Reset </Button>
        </div>
    </div>
</template>

<style scoped></style>
