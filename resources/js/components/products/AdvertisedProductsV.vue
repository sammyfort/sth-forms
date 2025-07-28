<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { cn } from '@/lib/utils';
import { HTMLAttributes, onMounted, ref } from 'vue';
import { ProductI } from '@/types';
import { getPromotedProducts } from '@/lib/api';
import ProductCardV1 from '@/components/products/ProductCardV1.vue';
import ProductCardSkeleton from '@/components/skeletons/ProductCardSkeleton.vue';

const plugin = Autoplay({
    delay: 5000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})

const props = defineProps<{
    itemsClass?: HTMLAttributes['class']
}>()

const products = ref<ProductI[]>([])
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
            loop: true,
        }"
        orientation="vertical"
        v-if="products.length"
    >
        <CarouselContent :class="cn('mb-5', props.itemsClass)">
            <CarouselItem class="basis-1/3" v-for="product in products" :key="product.id">
                <ProductCardV1
                    :product="product"
                    :carousel-plugin="plugin"
                    @popover-open="onPopoverStateChanged"
                />
                <template v-if="processing">
                    <ProductCardSkeleton
                        v-for="x in [1,2,3]"
                        :key="x"
                    />
                </template>
            </CarouselItem>
        </CarouselContent>
    </Carousel>

</template>

<style scoped>

</style>
