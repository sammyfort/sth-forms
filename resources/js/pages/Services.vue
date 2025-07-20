<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, router } from '@inertiajs/vue3';
import { BriefcaseBusiness, Signpost, Star } from 'lucide-vue-next';
import ServiceCardV1 from '@/components/Services/ServiceCardV1.vue';
import { PaginatedDataI, ServiceI } from '@/types';

const props = defineProps<{
    services: PaginatedDataI<ServiceI>
}>()

</script>

<template>
    <Head title="Artisans"/>
    <Layout>
        <div class="relative flex w-full gap-15">
            <div class="w-full text-gray-900 antialiased lg:w-4/5">
                <div class="mb-5">
                    <div class="text-fade text-2xl font-semibold">Browse Artisans</div>
                </div>
                <div v-if="services.data?.length" class="grid items-stretch gap-7 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4">
                    <ServiceCardV1
                        v-for="service in services.data"
                        :key="service.id"
                        :service="service"
                    />
                </div>
                <div class="flex w-full flex-col items-center gap-4" v-else>
                    <Signpost class="text-primary" :size="70" />
                    <div class="text-center">
                        <div class="mb-3 text-3xl font-bold">No Service Found</div>
                        <div>Broaden your search...</div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
