<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import VerifiedBadge from '@/components/icons/VerifiedBadge.vue';

const props = defineProps<{
    businesses: Array<{
        id: number;
        slug: string;
        name: string;
        email: string;
        mobile: string;
        description: string;
        facebook?: string;
        x?: string;
        linkedin?: string;
        instagram?: string;
        verified?: boolean,
    }>;
}>();
</script>

<template>
    <Head title="Businesses" />
    <Layout>
        <div class="w-full">
            <div class="text-gray-900 antialiased">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 items-center md:justify-center gap-7">

                    <div v-for="business in businesses" :key="business.id" class="overflow-hidden rounded-lg bg-white shadow-2xl">
                        <img
                            class="object-end h-48 w-full object-cover"
                            src="https://images.unsplash.com/photo-1570797197190-8e003a00c846?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=968&q=80"
                            alt="Home in Countryside"
                        />
                        <div class="p-6">
                            <div class="flex items-baseline">
                                <span
                                    class="inline-block rounded-full bg-teal-200 px-4 py-1 text-xs font-semibold tracking-wide text-teal-800 uppercase"
                                    >New</span
                                >
                                <div class="ml-2 text-xs font-semibold tracking-wide text-gray-600 uppercase">3 beds &bull; 2 baths</div>
                            </div>
                            <div class="flex items-center gap-1 mt-2">
                                <h4 class="truncate text-lg leading-tight font-semibold">
                                    {{ business.name }}
                                </h4>
                                <div class="relative top-[1px]">
                                    <VerifiedBadge :size="20" color="#f78509" />
                                </div>
                            </div>




                            <div class="mt-1">
                                <span>$1,900.00</span>
                                <span class="text-sm text-gray-600">/ wk</span>
                            </div>
                            <div class="mt-2 flex items-center">
                                <span class="font-semibold text-teal-600">
                                    <span>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </span>
                                </span>
                                <span class="ml-2 text-sm text-gray-600">34 reviews</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped></style>
