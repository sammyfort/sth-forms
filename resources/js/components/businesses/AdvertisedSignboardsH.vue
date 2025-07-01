<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import SignboardCardV1 from '@/components/businesses/SignboardCardV1.vue';
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { HTMLAttributes, onMounted, ref } from 'vue';
import { SignboardI } from '@/types';
import { router } from '@inertiajs/vue3';
import { getPromotedSignboards } from '@/lib/api';

import { cn } from '@/lib/utils';


const plugin = Autoplay({
    delay: 3000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})
const props = defineProps<{
    class?: HTMLAttributes['class'],
    containerClass?: HTMLAttributes['class'],
}>()
const signboards = ref<SignboardI[]>([])

onMounted(async ()=>{
    signboards.value = (await getPromotedSignboards()).signboards
})

const popoverIsOpen = ref(false)

const onPopoverStateChanged = (isOpen: boolean) => {
    popoverIsOpen.value = isOpen
}

const resumeCarouselPlay = ()=>{
    if (popoverIsOpen.value){
        plugin.stop()
    }
    else {
        plugin.reset()
        plugin.play()
    }
}

</script>

<template>
    <Carousel
        :plugins="[plugin]"
        @mouseenter="plugin.stop"
        @mouseleave="resumeCarouselPlay"
        :opts="{
            loop: true
        }"
        :class="cn('', props.containerClass)"
        class=" overflow-hidden"
    >
        <div class="mb-5">
            <slot />
        </div>
        <CarouselContent class="mb-5 shadow-none">
            <CarouselItem class="sm:basis-1/2 md:basis-1/3 lg:basis-1/4" v-for="signboard in signboards" :key="signboard.id">
                <SignboardCardV1
                    :is-advertised="true"
                    :class="cn('border border-secondary', props.class)"
                    :signboard="signboard"
                    :carousel-plugin="plugin"
                    @popover-open="onPopoverStateChanged"
                />
            </CarouselItem>
        </CarouselContent>
    </Carousel>
</template>

<style scoped>

</style>
