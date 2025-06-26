<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Layout from '@/layouts/Layout.vue';
import HeroSection from '@/components/home/HeroSection.vue';
import TrustedBusinessesSection from '@/components/home/TrustedBusinessesSection.vue';
import Purpose from '@/components/home/Purpose.vue';
import FeaturesIntro from '@/components/home/FeaturesIntro.vue';
import Feature1 from '@/components/home/Feature1.vue';
import Feature2 from '@/components/home/Feature2.vue';
import Feature3 from '@/components/home/Feature3.vue';
import Feature4 from '@/components/home/Feature4.vue';
import Ratings from '@/components/home/Ratings.vue';
import CustomerEngagement from '@/components/home/CustomerEngagement.vue';
</script>

<template>
    <Head title="Home" />
    <Layout class="p-0 lg:p-0 md:p-0" style="padding: 0 !important;" :is-full-width="true">
        <div class="w-full">

            <HeroSection />

            <div class="container px-4 lg:px-8 mx-auto max-w-screen-xl text-gray-700 overflow-x-hidden">
                <TrustedBusinessesSection />
                <FeaturesIntro />
                <Purpose />
                <Feature1 />
                <Feature2 />
                <Feature3 />
                <Feature4 />
                <Ratings />
                <CustomerEngagement />

                <div class="mt-24 flex flex-col-reverse md:flex-row items-start md:gap-x-10">
                    <div data-aos="zoom-in-right" class="md:w-5/12 aos-init aos-animate">
                        <div class="flex items-center gap-x-20 mb-5">
                            <span class="border border-gray-300 w-14 absolute"></span>
                            <h1 class="text-gray-400 tracking-widest text-sm">TESTIMONIAL</h1>
                        </div>
                        <h1 class="font-semibold text-secondary text-2xl lg:pr-40">What Businesses Are Saying</h1>
                        <p class="text-gray-500 my-5 lg:pr-36">The Signboard App has received glowing feedback from business owners and customers across the country.</p>
                        <p class="text-gray-500 my-5 lg:pr-36">Many businesses have seen increased visibility and customer engagement since joining the platform.</p>
                        <p class="text-gray-500 my-5 lg:pr-36">Have a great experience? Share your feedback with us.</p>
                        <button
                            class="flex items-center gap-x-3 pl-3 border-b border-l border-t border-primary text-primary font-medium my-4 btn-zoom rounded-full">
                            <span>Write your assessment</span>
                            <div class="border border-primary h-14 w-14 rounded-full flex items-center justify-center">
                                <svg viewBox="0 0 26 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5">
                                    <path
                                        d="M25.7071 8.70711C26.0976 8.31658 26.0976 7.68342 25.7071 7.2929L19.3431 0.928934C18.9526 0.538409 18.3195 0.538409 17.9289 0.928934C17.5384 1.31946 17.5384 1.95262 17.9289 2.34315L23.5858 8L17.9289 13.6569C17.5384 14.0474 17.5384 14.6805 17.9289 15.0711C18.3195 15.4616 18.9526 15.4616 19.3431 15.0711L25.7071 8.70711ZM-8.74228e-08 9L25 9L25 7L8.74228e-08 7L-8.74228e-08 9Z"
                                        fill="#F48C06"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                    <div data-aos="zoom-in-left" class="md:w-7/12 aos-init aos-animate">
                        <img src="/images/tmp/testimonials.png" class="md:w-10/12 mx-auto">
                    </div>
                </div>

            </div>
        </div>
    </Layout>
</template>
