<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { cn } from '@/lib/utils';
import { HTMLAttributes, onMounted, ref } from 'vue';
import { ServiceI } from '@/types';
import { getPromotedServices } from '@/lib/api';
import ServiceCardV1 from '@/components/Services/ServiceCardV1.vue';
import ServiceCardV1Skeleton from '@/components/skeletons/ServiceCardV1Skeleton.vue';

const plugin = Autoplay({
    delay: 5000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})

const props = defineProps<{
    itemsClass?: HTMLAttributes['class']
}>()

const services = ref<ServiceI[]>([])
const processing = ref<boolean>(false)

onMounted(async ()=>{
    processing.value = true
    services.value = (await getPromotedServices()).services
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
        v-if="services.length"
    >
        <CarouselContent :class="cn('mb-5', props.itemsClass)">
            <CarouselItem class="basis-1/3" v-for="service in services" :key="service.id">
                <ServiceCardV1
                    class="border border-secondary"
                    :service="service"
                    :carousel-plugin="plugin"
                    @popover-open="onPopoverStateChanged"
                />
                <template v-if="processing">
                    <ServiceCardV1Skeleton
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
