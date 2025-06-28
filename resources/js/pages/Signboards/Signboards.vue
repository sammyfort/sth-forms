<script setup lang="ts">

import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import SignboardCardV1 from '@/components/businesses/SignboardCardV1.vue';
import { PaginatedDataI, SignboardI } from '@/types';
import AdvertisedSignboardV from '@/components/businesses/AdvertisedSignboardV.vue';
import InputText from '@/components/InputText.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue} from '@/components/ui/select'
import { Button } from '@/components/ui/button';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'
import { onMounted } from 'vue';


type Props = {
    signboards: PaginatedDataI<SignboardI>
}

const props = defineProps<Props>()

onMounted(()=>{
    console.log(props.signboards)
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
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
                                        <InputText
                                            placeholder="Search signboard..."
                                            container-class="gap-0"
                                        />
                                        <Select>
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Signboard category" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectItem value="apple">
                                                        Dancing
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
                                                    <SelectItem value="apple">
                                                        Bono
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
                        v-for="signboard in signboards.data"
                        :key="signboard.id"
                        class="shadow-2xl"
                        image-height="30"
                    />
                </div>
            </div>

            <!-- Sticky Sidebar -->
            <div class="hidden lg:block w-1/5 ">
                <div class="sticky top-0">
<!--                    <AdvertisedSignboardV items-class="h-screen" />-->
                </div>
            </div>
        </div>

    </Layout>
</template>

<style scoped>

</style>
