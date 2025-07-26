<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { HTMLAttributes, onMounted, ref } from 'vue';

import { cn } from '@/lib/utils';
import ServiceCardV1 from '@/components/Services/ServiceCardV1.vue';
import { getPromotedJobs } from '@/lib/api';
import ServiceCardV1Skeleton from '@/components/skeletons/ServiceCardV1Skeleton.vue';
import JobCardV1Skeleton from '@/components/skeletons/JobCardV1Skeleton.vue';
import JobCardV1 from '@/components/jobs/JobCardV1.vue';


const plugin = Autoplay({
    delay: 3000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})
const props = defineProps<{
    class?: HTMLAttributes['class'],
    containerClass?: HTMLAttributes['class'],
}>()
const jobs = ref<any[]>([])
const processing = ref<boolean>(false)

onMounted(async ()=>{
    processing.value = true
    jobs.value = (await getPromotedJobs()).jobs
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
        v-if="jobs.length"
    >
        <div class="mb-5">
            <slot />
        </div>
        <CarouselContent class="mb-5 shadow-none">
            <CarouselItem class="sm:basis-1/2 md:basis-1/3 lg:basis-1/4" v-for="job in jobs" :key="`accordion-item-${job.id}`">
                <template v-if="processing">
                    <JobCardV1Skeleton
                        v-for="x in [1,2,3]"
                        :key="x"
                    />
                </template>
                <template v-else>
                    <JobCardV1
                        :job="job"
                    />
                </template>
            </CarouselItem>
        </CarouselContent>
    </Carousel>
</template>

<style scoped>

</style>
