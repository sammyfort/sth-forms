<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { HTMLAttributes, onMounted, ref } from 'vue';

import { cn } from '@/lib/utils';
import { getPromotedProducts } from '@/lib/api';
import ProductCardV1 from '@/components/products/ProductCardV1.vue';
import ProductCardSkeleton from '@/components/skeletons/ProductCardSkeleton.vue';


const plugin = Autoplay({
    delay: 3000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})
const props = defineProps<{
    class?: HTMLAttributes['class'],
    containerClass?: HTMLAttributes['class'],
}>()
const products = ref<any[]>([])
const processing = ref<boolean>(false)

onMounted(async ()=>{
    processing.value = true
    products.value = (await getPromotedProducts()).products
    processing.value = false
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
        try {
            plugin.reset()
            plugin.play()
        } catch (err){}
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
        v-if="products.length"
    >
        <div class="mb-5">
            <slot />
        </div>
        <CarouselContent class="mb-5 shadow-none">
            <CarouselItem class="sm:basis-1/2 md:basis-1/3 lg:basis-1/4" v-for="product in products" :key="product.id">
                <template v-if="processing">
                    <ProductCardSkeleton
                        v-for="x in [1,2,3]"
                        :key="x"
                    />
                </template>
                <template v-else>
                    <ProductCardV1
                        :product="product"
                    />
                </template>
            </CarouselItem>
        </CarouselContent>
    </Carousel>
</template>

<style scoped>

</style>
