<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { LayoutDashboard, FormInputIcon } from 'lucide-vue-next';
</script>

<template>
    <div class="w-full text-gray-700 ">
        <!-- Header -->
        <div
            class="flex flex-col max-w-screen-xl px-8 mx-auto md:items-center md:justify-between md:flex-row">
            <div class="flex flex-row items-center justify-between py-6">
                <div class="relative md:mt-8">
                    <a href="#"
                       class="text-xl text-white relative z-50 font-bold tracking-widest rounded-lg focus:outline-none focus:shadow-outline">
                        STH Research & Development
                    </a>
                    <svg viewBox="0 0 79 79" fill="none" xmlns="http://www.w3.org/2000/svg"
                         class="h-11 z-40 absolute -top-2 -left-3">
                        <path
                            d="M35.2574 2.24264C37.6005 -0.100501 41.3995 -0.100505 43.7426 2.24264L76.7574 35.2574C79.1005 37.6005 79.1005 41.3995 76.7574 43.7426L43.7426 76.7574C41.3995 79.1005 37.6005 79.1005 35.2574 76.7574L2.24264 43.7426C-0.100501 41.3995 -0.100505 37.6005 2.24264 35.2574L35.2574 2.24264Z"
                            fill="#65DAFF"></path>
                    </svg>
                </div>
                <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                        <path fill-rule="evenodd"
                              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                              clip-rule="evenodd">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="">
            <div class="max-w-screen-xl px-8 mx-auto flex flex-col lg:flex-row items-start">
                <div
                    class="text-white flex flex-col w-full lg:w-8/12 justify-center lg:pt-24 items-start text-center lg:text-left mb-5 md:mb-0">
                    <h1 data-aos="fade-right" data-aos-once="true"
                        class="my-4 text-5xl font-bold leading-tight text-secondary aos-init aos-animate">
                        <span class="text-[#008238]">Apply</span>
                        <span class="text-[#FFA800]"> to conduct research at STH</span>
                    </h1>
                    <p data-aos="fade-down" data-aos-once="true" data-aos-delay="300"
                       class="leading-normal text-2xl mb-8 aos-init aos-animate">
                        Join our hospital research center as a guest researcher and contribute to advancing medical knowledge. Submit your application online and get access to our facilities and mentorship programs.
                    </p>
                    <div data-aos="fade-up" data-aos-once="true" data-aos-delay="700"
                         class="w-full md:flex items-center justify-center lg:justify-start md:gap-x-5 aos-init aos-animate">

                        <div class="flex items-center justify-center gap-x-3 mt-5 md:mt-0 btn-zoom mb-5">
                            <Link
                                :href="route('buy-voucher.index')"
                                class=" bg-gray-50 text-[#5e4800] px-3 py-2 border rounded-xl hover:text-primary flex items-center gap-1 text-center"
                            >
                                <FormInputIcon  :size="16" class="text-[#5e4800]"/> Apply
                            </Link>

                        </div>
                    </div>
                </div>

                <!-- Hero Image -->
                <div id="hero-image" class="w-full lg:w-6/12 lg:-mt-10 relative">
                    <!-- Main Image: Researcher in Laboratory -->
                    <img data-aos="fade-up" data-aos-once="true" src="https://images.pexels.com/photos/4226092/pexels-photo-4226092.jpeg" class="w-10/12 mx-auto 2xl:-mb-20 aos-init aos-animate" alt="Researcher in Laboratory">

                    <!-- Floating Element 1: Laboratory Equipment -->
                    <div data-aos="fade-up" data-aos-delay="300" data-aos-once="true" class="absolute top-20 -left-6 sm:top-32 sm:left-10 md:top-40 md:left-16 lg:-left-0 lg:top-52 floating-4 aos-init aos-animate">
                        <img src="https://images.pexels.com/photos/4226093/pexels-photo-4226093.jpeg" class="bg-white bg-opacity-80 rounded-lg h-12 sm:h-16" alt="Laboratory Equipment">
                    </div>
                </div>

            </div>


        </div>
    </div>
</template>

<style scoped>

</style>
