<script setup lang="ts">

import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { PlusIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import CreateBusiness from '@/pages/Businesses/CreateBusiness.vue';
import VerifiedBadge from '@/components/icons/VerifiedBadge.vue';

const props = defineProps<{
    businesses: Array<{
        id: number;
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
    <Head title="My Businesses" />

    <Layout>
        <div class="px-4">
            <div class="flex justify-end mb-3">
                <CreateBusiness @created="$inertia.reload({ only: ['businesses'] })">
                    <Button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded shadow flex items-center gap-2">
                        <PlusIcon class="w-4 h-4" />
                        <span>Add Business</span>
                    </Button>
                </CreateBusiness>
            </div>


            <div class="flex flex-wrap items-center justify-center">
                <div
                    v-for="business in businesses"
                    :key="business.id"
                    class="flex-shrink-0 m-6 relative overflow-hidden bg-orange-500 rounded-lg max-w-xs shadow-lg group"
                >
                    <svg
                        class="absolute bottom-0 left-0 mb-8 scale-150 group-hover:scale-[1.65] transition-transform"
                        viewBox="0 0 375 283"
                        fill="none"
                        style="opacity: 0.1;"
                    >
                        <rect
                            x="159.52"
                            y="175"
                            width="152"
                            height="152"
                            rx="8"
                            transform="rotate(-45 159.52 175)"
                            fill="white"
                        />
                        <rect
                            y="107.48"
                            width="152"
                            height="152"
                            rx="8"
                            transform="rotate(-45 0 107.48)"
                            fill="white"
                        />
                    </svg>

                    <div
                        class="relative pt-10 px-10 flex items-center justify-center group-hover:scale-110 transition-transform"
                    >
                        <div
                            class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3"
                            style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;"
                        ></div>
                        <img
                            class="relative w-40 object-contain"
                            src="/images/business-icon.png"
                            alt="Business Briefcase"
                        />

                    </div>

                    <div class="relative text-white px-6 pb-6 mt-6">
                        <span class="block opacity-75 -mb-1">{{ business.mobile }}</span>
                        <div class="flex justify-between">
                            <span class="block font-semibold text-xl">{{business.name}}</span>
                            <VerifiedBadge   :size="24" color="#1D9BF0" />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>



<style scoped>

</style>
