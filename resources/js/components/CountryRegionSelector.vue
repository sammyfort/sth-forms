<script setup lang="ts">
import { HTMLAttributes, onMounted, ref } from 'vue';
import { getCountries, getRegions } from '@/lib/api';
import { Select, SelectContent, SelectTrigger, SelectValue } from '@/components/ui/select';
import { CountryI, RegionI } from '@/types';
import { LoaderCircle } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';


const props = defineProps<{
    form: Record<string, any>;
    countryModel?: string;
    regionModel?: string;
    class?: HTMLAttributes['class'];
}>();

const countries = ref([]);
const regions = ref([]);
const defaultRegion = ref(null);
const loadingCountries = ref(false);
const loadingRegions = ref(false);

async function handleCountryChange(countryId: number) {
    if (!props.regionModel) return;
    regions.value = []
    props.form[props.regionModel] = null
    loadingRegions.value = true
    regions.value = (await getRegions(countryId)).regions;
    loadingRegions.value = false
}

onMounted(async () => {
    loadingCountries.value = true
    countries.value = (await getCountries()).countries;
    loadingCountries.value = false

    if (props.countryModel && props.regionModel && props.form[props.countryModel]){
        if (props.form[props.countryModel]){
            defaultRegion.value = props.form[props.regionModel]
        }
        await handleCountryChange(props.form[props.countryModel])
    }
    props.form[props.regionModel] = defaultRegion.value
});
</script>

<template>
    <Select
        v-model="form[countryModel]"
        @update:model-value="handleCountryChange"
        :disabled="loadingCountries"
    >
        <SelectTrigger class="col-span-2 w-full md:col-span-1 lg:col-span-2">
            <SelectValue :placeholder="loadingRegions ? 'Loading countries...' : 'Select country'" />
        </SelectTrigger>
        <SelectContent
            :options="
                countries.map((country: CountryI) => {
                    return { label: country.name, value: country.id as unknown as string };
                })
            "
            slice="all"
        />
    </Select>
    <InputError v-if="countryModel" :message="form.errors[countryModel]" />

    <div v-if="loadingRegions && regionModel" class="flex w-100 text-sm items-center gap-2 text-fade">
        <span>Loading regions ...</span>
        <LoaderCircle :size="12" class="animate-spin" />
    </div>
    <Select
        v-if="regionModel && regions.length"
        v-model="form[regionModel]"
    >
        <SelectTrigger class="col-span-2 w-full md:col-span-1 lg:col-span-2">
            <SelectValue placeholder="Select region" />
        </SelectTrigger>
        <SelectContent
            :options="
                regions.map((region: RegionI) => {
                    return { label: region.name, value: region.id as unknown as string };
                })"
            slice="all"
        />
    </Select>
    <InputError v-if="regionModel" :message="form.errors[regionModel]" />
</template>

<style scoped></style>
