<script setup lang="ts">

</script>

<template>
    <div class="mt-28">
        <div data-aos="flip-down" class="text-center max-w-screen-md mx-auto aos-init aos-animate">
            <h1 class="text-3xl font-bold mb-4">What is <span class="text-primary">Signboard?</span></h1>
            <p class="text-gray-500">
                Signboard is a web platform that lets businesses showcase their physical signboards online, making them discoverable by customers based on location, category, and reviews.
            </p>
        </div>
        <div data-aos="fade-up"
             class="flex flex-col md:flex-row justify-center gap-y-5 md:gap-y-0 md:gap-x-6 lg:gap-x-10 mt-7 aos-init aos-animate">

            <!-- For Business Owners -->
            <div class="relative md:w-5/12">
                <img src="/images/tmp/business1.jpg" alt="" class="rounded-2xl">
                <div class="absolute bg-black/20 bottom-0 left-0 right-0 w-full h-full rounded-2xl">
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <h1 class="uppercase text-white font-bold text-center text-sm lg:text-xl mb-3">
                            FOR BUSINESS OWNERS</h1>
                        <button
                            class="rounded-full text-white bg-secondary/50 border border-primary text-xs lg:text-md px-6 py-3 w-full font-medium btn-zoom">
                            List Your Signboard
                        </button>
                    </div>
                </div>
            </div>

            <!-- For Customers -->
            <div class="relative md:w-5/12">
                <img src="/images/tmp/business2.jpg" alt="" class="rounded-2xl">
                <div class="absolute bg-black/20 bottom-0 left-0 right-0 w-full h-full rounded-2xl">
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <h1 class="uppercase text-white font-bold text-center text-sm lg:text-xl mb-3">
                            FOR CUSTOMERS</h1>
                        <button
                            class="rounded-full text-white bg-black/50 border border-white text-xs lg:text-md px-6 py-3 w-full font-medium btn-zoom">
                            Discover Nearby Businesses
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
