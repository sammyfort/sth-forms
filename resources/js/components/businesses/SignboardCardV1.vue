<script setup lang="ts">
import { cn } from '@/lib/utils';
import { HTMLAttributes, ref } from 'vue';
import { SignboardI } from '@/types';
import { MapPin, MapPinned, Pin, Dot, Handshake } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger} from '@/components/ui/tooltip'
import SignboardRating from '@/components/businesses/SignboardRating.vue';
import StarRating from 'vue-star-rating'
import { AutoplayType } from 'embla-carousel-autoplay';
import { router } from '@inertiajs/vue3';


const emit = defineEmits<{
    (e: 'popoverOpen', value: boolean): void
}>()

const onPopoverStateChanged = (isOpen: boolean) => {
    emit('popoverOpen', isOpen)
}

const props = withDefaults(
    defineProps<{
        class?: HTMLAttributes['class'],
        imageHeight? : string,
        signboard: SignboardI,
        isAdvertised?: boolean,
        carouselPlugin?: AutoplayType
    }>(),
    {
        imageHeight: "48",
        isAdvertised: false,
    }
)

const signboardC = ref(props.signboard)

const ratedHandler = (sb: SignboardI)=>{
    signboardC.value = sb
}
</script>

<template>
    <div :class="cn('overflow-hidden relative  rounded-lg bg-white shadow cursor-pointer transform transition-transform duration-300 hover:scale-99', props.class)">
        <TooltipProvider v-if="isAdvertised">
            <Tooltip>
                <TooltipTrigger as-child>
                    <div class="absolute top-3 p-1 rounded-[50%] bg-secondary right-3 text-white">
                        <Handshake :size="15"/>
                    </div>
                </TooltipTrigger>
                <TooltipContent side="bottom">
                    Trusted Business
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
        <img
            class="object-end w-full object-cover" :class="'h-'+imageHeight"
            src="https://images.unsplash.com/photo-1570797197190-8e003a00c846?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=968&q=80"
            alt="Home in Countryside"
            @click="router.visit(route('signboards.show', signboard.slug))"
        />
        <div class="p-4 flex flex-col gap-3">
            <div class="flex flex-wrap gap-x-2 gap-y-0 text-xs">
                <div class="flex items-center truncate" v-for="category in signboard.categories" :key="category.id">
                    <Dot class="text-primary h-5 w-5" :size="35"/>
                    <div>{{ category.name }}</div>
                </div>
            </div>

            <div>
                <div class="flex items-center gap-1">
                    <h4 class="truncate text-lg leading-tight font-semibold">
                        <Link
                            :href="route('signboards.show', signboard.slug)"
                            class="hover:underline hover:text-secondary"
                        >{{ signboard.business.name }}</Link>
                    </h4>
                </div>
                <div class="mt-1 flex gap-2 items-center text-sm">
                    <MapPin :size="15" class="text-primary"/>
                    <div>{{ signboard.region.name }} Region</div>
                </div>
                <div class="mt-1 flex gap-2 items-center text-sm">
                    <MapPinned :size="15" class="text-primary"/>
                    <div>{{ signboard.town }}</div>
                </div>
                <div class="mt-1 flex gap-2 items-center text-sm">
                    <Pin :size="15" class="text-primary"/>
                    <div>{{ signboard.street }}</div>
                </div>
            </div>

            <div class="flex flex-wrap items-center">
                <span>
                    <StarRating
                        :star-size="17"
                        :show-rating="false"
                        :rating="signboardC.total_average_rating"
                        read-only
                        active-color="#009689"
                        :rounded-corners="true"
                        :padding="3"
                        class="md:w-1/3 w-full"
                        :key="`rating-card-${signboard.id}`"
                        :increment="0.01"
                    />
                </span>
                <span class="w-1/3 ms-auto text-center text-sm text-gray-600">{{ signboardC.reviews_count }} reviews</span>
            </div>

            <div class="mt-auto ms-auto w-full flex items-baseline">
                <div class="flex flex-wrap items-center">
                    <SignboardRating
                        :signboard="signboardC" :id="`rating-pop-${signboard.id}`"
                        :carousel-plugin="carouselPlugin"
                        @popover-open="onPopoverStateChanged"
                        @rated="ratedHandler"
                    >
                        <span class="md:w-1/3 w-full text-primary underline text-sm font-semibold hover:text-secondary">Review</span>
                    </SignboardRating>
                </div>
                <Button size="sm" variant="secondary" as-child class="ms-auto mt-auto">
                    <Link :href="route('signboards.show', signboard.slug)" class=" text-primary" size="sm">View Signboard</Link>
                </Button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
