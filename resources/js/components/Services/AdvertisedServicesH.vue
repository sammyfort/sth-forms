<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { HTMLAttributes, onMounted, ref } from 'vue';

import { cn } from '@/lib/utils';
import SignboardCardV1Skeleton from '@/components/skeletons/SignboardCardV1Skeleton.vue';
import ServiceCardV1 from '@/components/Services/ServiceCardV1.vue';
import { getPromotedSignboards } from '@/lib/api';


const plugin = Autoplay({
    delay: 3000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})
const props = defineProps<{
    class?: HTMLAttributes['class'],
    containerClass?: HTMLAttributes['class'],
}>()
const services = ref<any[]>([])
const processing = ref<boolean>(false)

onMounted(async ()=>{
    processing.value = true
    services.value = (await getPromotedSignboards()).signboards
    services.value = [
        {
            id: 1,
            title: "Electrician",
            user_name: "Emmanuel Arhinful",
            land_landmark: "Opposite Melcom",
            city: "Sunyani",
            business_name: "TreySoft Systems",
            featured: "https://picsum.photos/200/300?1",
            is_advertised: true,
        },
        {
            id: 2,
            title: "Plumber",
            user_name: "Ama Serwaa",
            land_landmark: "Near Vodafone Office",
            city: "Kumasi",
            business_name: "Serwaa Plumbing Works",
            featured: "https://picsum.photos/200/300?2",
            is_advertised: true,
        },
        {
            id: 3,
            title: "Carpenter",
            user_name: "Kwame Mensah",
            land_landmark: "Behind Shell Fuel Station",
            city: "Accra",
            business_name: "Mensah Woodworks",
            featured: "https://picsum.photos/200/300?3",
            is_advertised: false,
        },
        {
            id: 4,
            title: "Mason",
            user_name: "Yaw Antwi",
            land_landmark: "Close to GCB Bank",
            city: "Takoradi",
            business_name: "Antwi Construction",
            featured: "https://picsum.photos/200/300?4",
            is_advertised: true,
        },
        {
            id: 5,
            title: "Painter",
            user_name: "Akua Dufie",
            land_landmark: "Opposite Central Market",
            city: "Cape Coast",
            business_name: "Dufie Decor",
            featured: "https://picsum.photos/200/300?5",
            is_advertised: true,
        },
        {
            id: 6,
            title: "Mechanic",
            user_name: "Kojo Oppong",
            land_landmark: "Beside Total Filling Station",
            city: "Tamale",
            business_name: "Oppong Auto Works",
            featured: "https://picsum.photos/200/300?6",
            is_advertised: false,
        },
        {
            id: 7,
            title: "Welder",
            user_name: "Esi Nyarko",
            land_landmark: "Near STC Yard",
            city: "Tema",
            business_name: "Nyarko Fabrications",
            featured: "https://picsum.photos/200/300?7",
            is_advertised: false,
        },
    ]
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
        v-if="services.length"
    >
        <div class="mb-5">
            <slot />
        </div>
        <CarouselContent class="mb-5 shadow-none">
            <CarouselItem class="sm:basis-1/2 md:basis-1/3 lg:basis-1/4" v-for="service in services" :key="service.id">
                <template v-if="processing">
                    <SignboardCardV1Skeleton
                        v-for="x in [1,2,3]"
                        :key="x"
                    />
                </template>
                <template v-else>
                    <ServiceCardV1
                        :service="service"
                    />
                </template>
            </CarouselItem>
        </CarouselContent>
    </Carousel>
</template>

<style scoped>

</style>
