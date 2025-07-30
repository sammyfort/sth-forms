<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import StarRating from 'vue-star-rating'
import { Eye, PhoneCall, CheckCheck, MapPin, LocateFixed, HeartHandshake, Ban } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { ProductI } from '@/types';
import { cediSign, ucFirst } from '@/lib/helpers';
import { Carousel, CarouselContent, CarouselItem } from '@/components/ui/carousel';
import Autoplay from 'embla-carousel-autoplay'
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

type Props = {
    product: ProductI
}

const props = defineProps<Props>()

const totalAverageRating = ref<number>(props.product.total_average_rating)

const plugin = Autoplay({
    delay: 3000,
    stopOnMouseEnter: false,
    stopOnInteraction: false,
})

</script>

<template>
    <Card
        :class="cn('transform cursor-pointer p-2 bg-gray-50 rounded-none border-none shadow-none hover:shadow-2xl hover:bg-white transition-all duration-300 hover:scale-99')"
        @click="router.visit(route('products.show', product.slug))"
    >
        <CardContent class="flex flex-col gap-2 p-0 pb-4 hover:bg-gray-50">
            <div class="relative h-65 overflow-hidden">
                <div class="relative h-full w-full">
                    <img :src="product.featured as string" alt="Service image" class="h-full w-full object-cover rounded-xs" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                </div>
            </div>
            <div class="text-sm truncate">{{ ucFirst(product.name) }}</div>
            <div class="flex gap-1.5 flex-col">
                <div class="flex items-center gap-3">
                    <div class="font-bold">
                        <span class="text-xs">{{ cediSign() }}</span>
                        <span class="text-lg">{{ product.price }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-secondary font-semibold">
                        <Eye :size="13"/>
                        <span class="text-xs">{{ product.views_count }}</span>
                    </div>
                    <PhoneCall :size="20" class="ms-auto text-primary"/>
                </div>

                <div class="text-sm">
                    <Carousel
                        :plugins="[plugin]"
                        :opts="{ loop: true }"
                        orientation="vertical"
                    >
                        <CarouselContent class="h-10 text-[#633780] text-sm">
                            <CarouselItem key="d" class="basis-1" >
                                <div class="flex gap-1.5 items-center">
                                    <MapPin :size="15"/>
                                    <span>{{ product.region.name }}</span>
                                </div>
                            </CarouselItem>
                            <CarouselItem key="c" class="basis-1" >
                                <div class="flex gap-1.5 items-center">
                                    <LocateFixed :size="15"/>
                                    <span>{{ ucFirst(product.town) }}</span>
                                </div>
                            </CarouselItem>
                            <CarouselItem key="d" class="basis-1" >
                                <div v-if="product.is_negotiable" class="flex items-center gap-1.5">
                                    <HeartHandshake :size="15" />
                                    <span>Negotiable</span>
                                </div>
                                <div v-else class="flex items-center gap-1.5">
                                    <Ban :size="15" />
                                    <span>Non-negotiable</span>
                                </div>
                            </CarouselItem>
                        </CarouselContent>
                    </Carousel>
                </div>
                <div class="flex items-center gap-4">
                    <StarRating
                        :star-size="15"
                        :show-rating="false"
                        :rating="totalAverageRating"
                        read-only
                        active-color="#009689"
                        :padding="3"
                        class="md:w-1/3 w-full"
                        :key="`rating-card-${product.id}`"
                        :increment="0.01"
                    />
                    <span class="text-fade text-xs" v-if="product.reviews_count">{{ product.reviews_count }}</span>
                </div>
                <div v-if="product.active_promotion">
                    <Badge class="bg-[#633780] text-white rounded-sm text-xs">
                        <CheckCheck :size="10" class="text-primary"/>
                        <span>Popular seller</span>
                    </Badge>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped></style>
