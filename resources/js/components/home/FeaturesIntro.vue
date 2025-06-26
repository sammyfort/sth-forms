<script setup lang="ts">
import { Telescope, MapPinned, ChartNoAxesCombined } from 'lucide-vue-next';

</script>

<template>
    <div data-aos="flip-up" class="max-w-xl mx-auto text-center mt-24 aos-init aos-animate">
        <h1 class="font-bold text-secondary my-3 text-2xl">All-In-One <span class="text-primary">Signboard Platform.</span></h1>
        <p class="leading-relaxed text-gray-500">Signboard Ghana offers everything you need to showcase your business location and signage to online visitors.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-14 md:gap-5 mt-20">
        <!-- Feature 1 -->
        <div data-aos="fade-up" class="bg-white shadow-xl p-6 text-center rounded-xl aos-init aos-animate">
            <div class="rounded-full w-16 h-16 flex items-center justify-center mx-auto shadow-lg transform -translate-y-12"
                 style="background: rgb(91, 114, 238);">
                <Telescope class="text-white"/>
            </div>
            <h1 class="font-medium text-xl mb-3 lg:px-14 text-secondary">Manage Your Listings</h1>
            <p class="px-4 text-gray-500">Easily add, edit, or remove your signboard listings to keep your business info up to date and relevant.</p>
        </div>
        <!-- Feature 2 -->
        <div data-aos="fade-up" data-aos-delay="150"
             class="bg-white shadow-xl p-6 text-center rounded-xl aos-init aos-animate">
            <div class="rounded-full w-16 h-16 flex items-center justify-center mx-auto shadow-lg transform -translate-y-12"
                 style="background: rgb(244, 140, 6);">
                <MapPinned class="text-white"/>
            </div>
            <h1 class="font-medium text-xl mb-3 lg:px-14 text-secondary">Map-Based Discovery</h1>
            <p class="px-4 text-gray-500">
                Help users explore businesses nearby by viewing signboards on an interactive map with filters for category and location.
            </p>
        </div>
        <!-- Feature 3 -->
        <div data-aos="fade-up" data-aos-delay="300"
             class="bg-white shadow-xl p-6 text-center rounded-xl aos-init aos-animate">
            <div class="rounded-full w-16 h-16 flex items-center justify-center mx-auto shadow-lg transform -translate-y-12"
                 style="background: rgb(41, 185, 231);">
                <ChartNoAxesCombined class="text-white"/>
            </div>
            <h1 class="font-medium text-xl mb-3 lg:px-14 text-secondary lg:h-14">Track Customer Interactions</h1>
            <p class="px-4 text-gray-500">Gain insights on how many people viewed your signboard and follow up with interested clients in real-time.</p>
        </div>
    </div>
</template>

<style scoped>

</style>
