<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import SignboardCardV1 from '@/components/businesses/SignboardCardV1.vue';
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { cn } from '@/lib/utils';
import { HTMLAttributes, onMounted, ref } from 'vue';
import { SignboardI } from '@/types';
import { getApi } from '@/lib/api';

const plugin = Autoplay({
    delay: 5000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})

const props = defineProps<{
    itemsClass?: HTMLAttributes['class']
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
        orientation="vertical"
    >
        <CarouselContent :class="cn('mb-5 h-[450px]', props.itemsClass)">
            <CarouselItem class="md:basis-1/2 lg:basis-1/3" v-for="signboard in signboards" :key="signboard.id">
                <SignboardCardV1 class="border border-secondary" :signboard="signboard"/>
            </CarouselItem>
        </CarouselContent>
    </Carousel>

</template>

<style scoped>

</style>
