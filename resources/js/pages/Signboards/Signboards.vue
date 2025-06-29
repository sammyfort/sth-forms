<script setup lang="ts">

import Layout from '@/layouts/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import SignboardCardV1 from '@/components/businesses/SignboardCardV1.vue';
import { PaginatedDataI, RegionI, SignboardCategoryI, SignboardI } from '@/types';
import AdvertisedSignboardV from '@/components/businesses/AdvertisedSignboardV.vue';
import InputText from '@/components/InputText.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue} from '@/components/ui/select'
import { Button } from '@/components/ui/button';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'
import { onMounted, onUnmounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import SignboardCardV1Skeleton from '@/components/skeletons/SignboardCardV1Skeleton.vue';
import BackToTop from '@/components/BackToTop.vue';
import { number } from 'motion-v';


type Props = {
    signboardsData: PaginatedDataI<SignboardI>
    categories: SignboardCategoryI[]
    regions: RegionI[]
}

const props = defineProps<Props>()

const signboards = ref<SignboardI[]>([])
const loadingSignboards = ref<boolean>(false)
const nextPage = ref<string|null>(null)
const filterForm = useForm({
    q: "",
    category: null,
    region: null
})

const handleScroll = () => {
    if (loadingSignboards.value || !nextPage.value) return;

    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
        router.get(nextPage.value, {}, {
            onStart: () => loadingSignboards.value = true,
            onFinish: () => loadingSignboards.value = false,
            preserveState: true,
            preserveScroll: true,
            preserveUrl: true,
            only: ["signboardsData"],
            onSuccess: (page) => {
                const resSignboardsData = page.props.signboardsData as PaginatedDataI<SignboardI>
                nextPage.value = resSignboardsData.next_page_url
                signboards.value = [...signboards.value, ...resSignboardsData.data as SignboardI[]] as SignboardI[]

                // if (filterQuery && netPageUrl){
                //     netPageUrl = netPageUrl + filterQuery.replace('?', '&')
                // }
                // setBets((prev) => [...prev, ...page.props.bets.data]);
                // setNextPage(netPageUrl);
                // setLoading(false);
            },
        });
    }
};

onMounted(()=>{
    nextPage.value = props.signboardsData.next_page_url
    signboards.value = props.signboardsData.data as SignboardI[]

    window.removeEventListener('scroll', handleScroll) // this here will help prevent duplicate binding
    window.addEventListener('scroll', handleScroll)
})

onUnmounted(()=>{
    window.removeEventListener("scroll", handleScroll)
})
</script>

<template>
    <Head title="Signboards"/>
    <Layout>
        <div class="flex gap-15 relative">
            <div class="w-full lg:w-4/5 text-gray-900 antialiased">
                <div class="mb-5">
                    <div class="text-2xl font-semibold text-fade">Browse Signboards</div>
                    <div class="mt-3">
                        <Accordion type="single" collapsible>
                            <AccordionItem value="filter">
                                <AccordionTrigger class="justify-start items-center gap-1">
                                    <div class="text-lg font-semibold text-fade cursor-pointer">Filter:</div>
                                </AccordionTrigger>
                                <AccordionContent>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 items-center p-2">
                                        <InputText
                                            placeholder="Search signboard..."
                                            container-class="gap-0"
                                            type="text"
                                            model="q"
                                            :form="filterForm"
                                        />
                                        <Select>
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Signboard category" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectItem
                                                        v-for="category in categories"
                                                        :key="category.id"
                                                        :value="category.id"
                                                    >
                                                        {{ category.name }}
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                        <Select>
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Region" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectItem
                                                        v-for="region in regions"
                                                        :key="region.id"
                                                        :value="region.id"
                                                    >
                                                        {{ region.name }}
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                        <Button variant="secondary">Apply Filter</Button>
                                    </div>
                                </AccordionContent>
                            </AccordionItem>
                        </Accordion>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 items-start gap-7 items-stretch">
                    <SignboardCardV1
                        :signboard="signboard"
                        v-for="signboard in signboards"
                        :key="signboard.id"
                        class="shadow-2xl"
                        image-height="30"
                    />
                    <SignboardCardV1Skeleton
                        v-show="loadingSignboards"
                        v-for="sk in [1,2,3,4]"
                        :key="sk"
                        class="shadow-2xl"
                        image-height="30"
                    />
                </div>
            </div>

            <!-- Sticky Sidebar -->
            <div class="hidden lg:block w-1/5 ">
                <div class="sticky top-0">
                    <AdvertisedSignboardV items-class="h-screen" />
                </div>
            </div>
        </div>
    </Layout>
    <BackToTop />
</template>

<style scoped>

</style>
