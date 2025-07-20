<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import HeroSection from '@/components/home/HeroSection.vue';
import InputSelect from '@/components/InputSelect.vue';
import { InputSelectOption } from '@/types';
import { useForm } from '@inertiajs/vue3';
import InputText from '@/components/InputText.vue';
import TrustedBusinessesSection from '@/components/home/TrustedBusinessesSection.vue';
import AdvertisedServicesH from '@/components/Services/AdvertisedServicesH.vue';
import { router } from '@inertiajs/vue3';

const searchForm = useForm({
    directory: ''
})

const searchOptions: InputSelectOption[] = [
    {label: 'Artisans', value: 'artisans'},
    {label: 'Signboards', value: 'signboards'},
    {label: 'Jobs', value: 'jobs'},
    {label: 'Shops', value: 'shops'},
]

</script>

<template>
  <Layout class="p-0 lg:p-0 md:p-0" style="padding: 0 !important;" :is-full-width="true">
        <div class="w-full">
            <HeroSection />

            <div class="px-3 py-10">
                <div class="text-2xl font-semibold mb-5 text-center">Browse directories</div>
                <div class="flex justify-center p-10 gap-7 flex-wrap">
                    <InputSelect
                        :options="searchOptions"
                        label="Directory"
                        :form="searchForm"
                        model="categories"
                        class="min-w-100"
                    />
                    <InputText
                        label="Search For"
                        container-class="min-w-100"
                        placeholder="Type what you're looking for..."
                    />
                </div>
            </div>

            <div class="px-3 py-10">
                <div class="text-2xl font-semibold mb-5 text-center">What are you looking for ?</div>
                <div class="flex flex-wrap justify-center p-10 gap-7">
                    <div
                        @click="router.visit(route('services.index'))"
                        class="flex items-center transition-all ease-in-out duration-500 hover:bg-primary hover:text-secondary cursor-pointer justify-center font-bold rounded-[100%] h-[120px] w-[120px] p-10 bg-gray-300"
                    >
                        <span>Artisans</span>
                    </div>
                    <div
                        @click="router.visit(route('signboards.index'))"
                        class="flex items-center transition-all ease-in-out duration-500 hover:bg-primary hover:text-secondary cursor-pointer justify-center font-bold rounded-[100%] h-[120px] w-[120px] p-10 bg-gray-300"
                    >
                        <span>Signboard</span>
                    </div>
                    <div class="flex items-center transition-all ease-in-out duration-500 hover:bg-primary hover:text-secondary cursor-pointer justify-center font-bold rounded-[100%] h-[120px] w-[120px] p-10 bg-gray-300">
                        <span>Job</span>
                    </div>
                    <div class="flex items-center transition-all ease-in-out duration-500 hover:bg-primary hover:text-secondary cursor-pointer justify-center font-bold rounded-[100%] h-[120px] w-[120px] p-10 bg-gray-300">
                        <span>Buy</span>
                    </div>
                </div>
            </div>
            <div class="container px-4 lg:px-0 mx-auto max-w-screen-xl text-gray-700">
                <TrustedBusinessesSection />
            </div>
            <div class="container px-4 lg:px-0 mx-auto max-w-screen-xl text-gray-700 mt-20 mb-20">
                <h1 class="text-center mb-3 text-gray-400 text-2xl font-medium ">Top Artisans</h1>
<!--                <div class="grid items-stretch gap-7 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4">-->
<!--                </div>-->
                <AdvertisedServicesH />
            </div>
        </div>
  </Layout>
</template>
