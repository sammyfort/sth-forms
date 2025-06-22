<script setup lang="ts">
import TopNavbar from '@/layouts/TopNavbar.vue';
import { Toaster } from '@/components/ui/sonner'
import { onMounted } from 'vue';
import GLightbox from 'glightbox';

type Props = {
    centerX?: boolean,
    centerY?: boolean,
}

const props = withDefaults(defineProps<Props>(), {
    centerX: false,
    centerY: false,
})

onMounted(() => {
    GLightbox({
        selector: '.glightbox',
    })
})
</script>

<template>
    <Toaster position="top-right" richColors/>
    <div class="flex min-h-screen flex-col items-center p-6  lg:justify-center lg:p-8 page">
        <a class="hidden bg-gradient-to-b from-blue-500 to-blue-600 px-2 py-3 text-center text-sm text-white md:block lg:font-[500] w-full"
           href="/pricing"
        >
            Notice Board
        </a>
        <TopNavbar />
        <div class="flex w-full opacity-100 mt-5 transition-opacity duration-750 lg:grow starting:opacity-0"
             :class="{'justify-center': props.centerX, 'items-center': props.centerY}"
        >
            <main v-if="props.centerX">
                <slot />
            </main>
            <slot v-else/>
        </div>
    </div>
</template>

<style scoped>

</style>
