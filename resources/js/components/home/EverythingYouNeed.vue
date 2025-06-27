<script setup lang="ts">

</script>

<template>
    <div class="sm:flex items-center sm:gap-x-8 mt-36">
        <div data-aos="fade-right" class="sm:w-1/2 relative aos-init aos-animate">
            <div class="bg-primary rounded-full absolute w-12 h-12 z-0 -left-4 -top-3 animate-pulse"></div>
            <h1 class="font-semibold text-2xl relative z-50 text-secondary lg:pr-10">
                Everything you need to showcase your business, <span class="text-primary">right from your signboard</span>
            </h1>
            <p class="py-5 lg:pr-32">
                Signboard helps local businesses in Ghana take their visibility online by letting them upload, manage, and promote their physical signboards digitally — searchable by location, category, and reviews.
            </p>
            <button data-aos="flip-up"
                    class="px-5 py-3 border border-primary text-primary font-medium my-14 btn-zoom bg-secondary rounded-full aos-init aos-animate">
                Get Started
            </button>
        </div>
        <!-- Right Image & Play Button -->
        <div data-aos="fade-left" class="sm:w-1/2 relative mt-10 sm:mt-0 aos-init aos-animate">
            <div class="floating w-24 h-24 absolute rounded-lg z-0 -top-3 -left-3" style="background: rgb(35, 189, 238);"></div>
            <img src="/images/tmp/image112.jpeg" alt="Signboard Demo" class="rounded-xl z-40 relative">
            <!-- Play Button -->
            <button
                class="bg-white w-14 h-14 rounded-full flex items-center justify-center absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 btn-zoom z-50">
                <svg viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1">
                    <path
                        d="M22.5751 12.8097C23.2212 13.1983 23.2212 14.135 22.5751 14.5236L1.51538 27.1891C0.848878 27.5899 5.91205e-07 27.1099 6.25202e-07 26.3321L1.73245e-06 1.00123C1.76645e-06 0.223477 0.848877 -0.256572 1.51538 0.14427L22.5751 12.8097Z"
                        fill="#23BDEE"></path>
                </svg>
            </button>
            <!-- Floating Square -->
            <div class="bg-primary w-40 h-40 floating absolute rounded-lg z-10 -bottom-3 -right-3"></div>
        </div>
    </div>
</template>

<style scoped>

</style>
