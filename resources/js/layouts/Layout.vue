<script setup lang="ts">
import TopNavbar from '@/layouts/TopNavbar.vue';
import { Toaster } from '@/components/ui/sonner'
import { onMounted, type HTMLAttributes } from 'vue';
import GLightbox from 'glightbox';
import { cn } from '@/lib/utils';
import Footer from '@/layouts/Footer.vue';

type Props = {
    centerX?: boolean,
    centerY?: boolean,
    class?: HTMLAttributes['class'],
    style?: HTMLAttributes['style'],
    isFullWidth?: boolean,
}

const props = withDefaults(defineProps<Props>(), {
    centerX: false,
    centerY: false,
    isFullWidth: false,
})

const showNotice = false;

onMounted(() => {
    GLightbox({
        selector: '.glightbox',
    })
})
</script>


<template>
    <Toaster position="top-right" richColors />

    <div class="relative flex min-h-screen flex-col items-center p-3 lg:justify-center lg:p-8 page">
        <!-- Background layer -->
        <div
            class="absolute inset-0 bg-[url('/images/slides4_d.jpg')] bg-no-repeat bg-cover bg-fixed blur-sm"
        ></div>

        <!-- Optional dark overlay (if you want to dim the background a bit) -->
        <!-- <div class="absolute inset-0 bg-black/20"></div> -->

        <!-- Foreground content -->
        <div
            :class="cn(props.class, 'relative z-10 flex min-h-screen flex-col items-center w-full')"
            :style="props.style"
        >
            <a
                class="hidden bg-gradient-to-b from-blue-500 to-blue-600 px-2 py-3 text-center text-sm text-white md:block lg:font-[500] w-full"
                href="/pricing"
                v-if="showNotice"
            >
                Notice Board
            </a>

            <TopNavbar
                :class="{
          'max-w-[1700px]': props.isFullWidth,
          'max-w-[1900px]': !props.isFullWidth,
        }"
            />

            <div
                class="flex w-full opacity-100 mt-5 transition-opacity duration-750 lg:grow starting:opacity-0"
                :class="{
          'justify-center': props.centerX,
          'items-center': props.centerY,
          'max-w-[1900px]': !props.isFullWidth,
        }"
            >
                <main v-if="props.centerX">
                    <slot />
                </main>
                <slot v-else />
            </div>
        </div>
    </div>
    <!-- <Footer /> -->
</template>


<style scoped>

</style>
