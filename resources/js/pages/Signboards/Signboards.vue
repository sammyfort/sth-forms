<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import SignboardCardV1 from '@/components/businesses/SignboardCardV1.vue';
import { PaginatedDataI, RegionI, SignboardCategoryI, SignboardI } from '@/types';
import AdvertisedSignboardV from '@/components/businesses/AdvertisedSignboardV.vue';
import InputText from '@/components/InputText.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { onBeforeMount, onMounted, onUnmounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import SignboardCardV1Skeleton from '@/components/skeletons/SignboardCardV1Skeleton.vue';
import BackToTop from '@/components/BackToTop.vue';
import { query } from '@vortechron/query-builder-ts';
import { Signpost, Star, BriefcaseBusiness } from 'lucide-vue-next';
import AdvertisedSignboardsH from '@/components/businesses/AdvertisedSignboardsH.vue';
import { chunkArray } from '@/lib/helpers';

type Props = {
    signboardsData: PaginatedDataI<SignboardI>;
    categories: SignboardCategoryI[];
    regions: RegionI[];
};

const props = defineProps<Props>();

const signboards = ref<SignboardI[]>([]);
const loadingSignboards = ref<boolean>(false);
const nextPage = ref<string | null>(null);
const filterForm = useForm<{
    q: string | null;
    categories: Array<number> | null;
    region: number | null;
}>({
    q: '',
    categories: null,
    region: null,
});
const filteredQuery = ref<string | null>(null);

const handleScroll = async () => {
    if (loadingSignboards.value || !nextPage.value) return;

    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
        if (!nextPage.value) return;
        router.get(
            nextPage.value,
            {},
            {
                onStart: () => (loadingSignboards.value = true),
                onFinish: () => (loadingSignboards.value = false),
                preserveState: true,
                preserveScroll: true,
                preserveUrl: true,
                only: ['signboardsData'],
                onSuccess: (page) => {
                    const resSignboardsData = page.props.signboardsData as PaginatedDataI<SignboardI>;
                    signboards.value = [...signboards.value, ...(resSignboardsData.data as SignboardI[])] as SignboardI[];

                    let nextPageUrl = resSignboardsData.next_page_url;
                    if (filteredQuery.value && nextPageUrl) {
                        nextPageUrl = nextPageUrl + filteredQuery?.value.replace('?', '&');
                    }
                    nextPage.value = nextPageUrl;
                },
            },
        );
    }
};

const handlerFilter = () => {
    const queryBuilder = query()
        .when(filterForm.region !== null, (query) => {
            query.filter('region', filterForm.region as unknown as string);
        })
        .when(filterForm.categories !== null && filterForm.categories.length > 0, (query) => {
            const cats = (filterForm.categories as []) ?? [];
            console.log(cats);
            cats.forEach((cat) => {
                query.filter('categories', cat as unknown as string);
            });
        })
        .when(filterForm.q !== null && filterForm.q !== '', (query) => {
            query.filter('q', filterForm.q as unknown as string);
        });

    const queryUrl = queryBuilder.build().replace('/', '');
    if (!queryUrl || queryUrl === '/') {
        filteredQuery.value = null;
        router.visit(route('signboards.index'));
    } else {
        signboards.value = [];
        filteredQuery.value = queryUrl;
        console.log('queryUrl', queryUrl);

        router.visit(route('signboards.index') + decodeURIComponent(queryBuilder.build()), {
            preserveState: true,
            preserveScroll: true,
            only: ['signboardsData'],
            onSuccess: (response) => {
                const filteredSignboards: PaginatedDataI<SignboardI> = response.props.signboardsData as PaginatedDataI<SignboardI>;
                nextPage.value = null;
                if (filteredSignboards.next_page_url) {
                    nextPage.value = filteredSignboards.next_page_url + queryUrl.replace('?', '&');
                }
                signboards.value = filteredSignboards.data as SignboardI[];
            },
            onStart: () => (loadingSignboards.value = true),
            onFinish: () => (loadingSignboards.value = false),
        });
    }
};

onMounted(() => {
    nextPage.value = props.signboardsData.next_page_url;
    signboards.value = props.signboardsData.data as SignboardI[];

    window.removeEventListener('scroll', handleScroll); // this here will help prevent duplicate binding
    window.addEventListener('scroll', handleScroll);
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

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <Head title="Signboards" />
    <Layout>
        <div class="relative flex w-full gap-15">
            <div class="w-full text-gray-900 antialiased lg:w-4/5">
                <div class="mb-5">
                    <div class="text-fade text-2xl font-semibold">Browse Signboards</div>
                    <div class="mt-3">
                        <Accordion type="single" collapsible :default-value="filteredQuery ? 'filter' : ''">
                            <AccordionItem value="filter">
                                <AccordionTrigger class="items-center justify-start gap-1">
                                    <div class="text-fade cursor-pointer text-lg font-semibold">Filter:</div>
                                </AccordionTrigger>
                                <AccordionContent>
                                    <div class="grid grid-cols-1 items-center gap-3 p-2 md:grid-cols-2 lg:grid-cols-4">
                                        <InputText
                                            placeholder="Search signboard..."
                                            container-class="gap-0"
                                            type="text"
                                            model="q"
                                            :form="filterForm"
                                        />
                                        <Select v-model="filterForm.categories" :default-value="filterForm.categories" :multiple="true">
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Signboard category" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                                                    {{ category.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <Select v-model="filterForm.region" :default-value="filterForm.region">
                                            <SelectTrigger class="w-full">
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
                                        <div class="grid grid-cols-2 gap-3">
                                            <Button
                                                v-show="filterForm.region || filterForm.categories?.length || filterForm.q?.length"
                                                @click="handlerFilter"
                                                >Apply Filter</Button
                                            >
                                            <Button v-show="filteredQuery" variant="secondary" @click="router.visit(route('signboards.index'))"
                                                >Reset</Button
                                            >
                                        </div>
                                    </div>
                                </AccordionContent>
                            </AccordionItem>
                        </Accordion>
                    </div>
                </div>
                <div class="grid items-stretch gap-7 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4">
                    <template v-for="(signboardsChunk, chunkIndex) in chunkArray<SignboardI>(signboards, 15)" :key="`chunk-${chunkIndex}`">
                        <SignboardCardV1
                            :signboard="signboard"
                            v-for="signboard in signboardsChunk"
                            :key="signboard.id"
                            class="shadow-2xl"
                        />
                        <AdvertisedSignboardsH
                            containerClass="md:col-span-3 lg:col-span-3 xl:col-span-4 sm:col-span-2 lg:hidden"
                            v-show="signboardsChunk.length > 14"
                        >
                            <div class="flex items-center gap-2 lg:text-2xl text-lg font-bold">
                                <BriefcaseBusiness :size="25" class="text-primary"/>
                                <span>Trusted Business</span>
                                <Star :size="25" class="text-primary"/>
                            </div>
                        </AdvertisedSignboardsH>
                    </template>
                    <SignboardCardV1Skeleton v-show="loadingSignboards" v-for="sk in [1, 2, 3, 4]" :key="sk" class="shadow-2xl" image-height="30" />
                </div>
                <div class="flex w-full flex-col items-center gap-4" v-if="!signboards.length && !loadingSignboards">
                    <Signpost class="text-primary" :size="70" />
                    <div class="text-center">
                        <div class="mb-3 text-3xl font-bold">No Signboard Found</div>
                        <div>Broaden your search...</div>
                    </div>
                </div>
            </div>

            <div class="hidden w-1/5 lg:block">
                <div class="sticky top-0">
                    <AdvertisedSignboardV items-class="h-screen" />
                </div>
            </div>
        </div>
    </Layout>
    <BackToTop />
</template>

<style scoped></style>
