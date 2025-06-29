<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import SignboardCardV1 from '@/components/businesses/SignboardCardV1.vue';
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { HTMLAttributes, onMounted, ref } from 'vue';
import { SignboardI } from '@/types';
import { router } from '@inertiajs/vue3';
import { getApi } from '@/lib/api';

const plugin = Autoplay({
    delay: 3000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})
const props = defineProps<{
    class?: HTMLAttributes['class'],
}>()
const signboards = ref<SignboardI[]>([])

onMounted(async ()=>{
    signboards.value = (await getApi('promotedSignboards')).metadata.signboards
})
</script>

<template>
    <Carousel
        :plugins="[plugin]"
        @mouseenter="plugin.stop"
        @mouseleave="[plugin.reset(), plugin.play(),];"
        :opts="{
            loop: true,
        }"
    >
        <CarouselContent class="mb-5 shadow-none">
            <CarouselItem class="md:basis-1/2 lg:basis-1/4" v-for="signboard in signboards" :key="signboard.id">
                <SignboardCardV1 :class="props.class" :signboard="signboard"/>
            </CarouselItem>
        </CarouselContent>
    </Carousel>
</template>

<style scoped>

</style>
