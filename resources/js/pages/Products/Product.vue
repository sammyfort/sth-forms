<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { MediaI, ProductI } from '@/types';
import RateDialog from '@/components/RateDialog.vue';
import { watchOnce } from '@vueuse/core'
import { ref } from 'vue';
import { Carousel, type CarouselApi, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import { Button } from '@/components/ui/button';
import { cediSign } from '@/lib/helpers';
import StarRating from 'vue-star-rating';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import ReviewsList from '@/components/review/ReviewsList.vue';
import { Store } from 'lucide-vue-next';
import AdvertisedProductsH from '@/components/products/AdvertisedProductsH.vue';
import ShareToSocials from '@/components/ShareToSocials.vue';
import VideoEmbed from '@/components/VideoEmbed.vue';

type Props = {
    product: ProductI
    media: MediaI[]
}

const props = defineProps<Props>()

const emblaMainApi = ref<CarouselApi>()
const emblaThumbnailApi = ref<CarouselApi>()
const selectedIndex = ref(0)

function onSelect() {
    if (!emblaMainApi.value || !emblaThumbnailApi.value)
        return
    selectedIndex.value = emblaMainApi.value.selectedScrollSnap()
    emblaThumbnailApi.value.scrollTo(emblaMainApi.value.selectedScrollSnap())
}

function onThumbClick(index: number) {
    if (!emblaMainApi.value || !emblaThumbnailApi.value)
        return
    emblaMainApi.value.scrollTo(index)
}

watchOnce(emblaMainApi, (emblaMainApi) => {
    if (!emblaMainApi)
        return

    onSelect()
    emblaMainApi.on('select', onSelect)
    emblaMainApi.on('reInit', onSelect)
})

function whatsappLink(){
    if (props.product.whatsapp_mobile){
        const message = `Hello, please I am inquiring about your product: ${props.product.name}\n\nProduct link:\n${route('products.show', props.product.id)}`;
        const encodedMessage = encodeURIComponent(message);
        return `https://wa.me/${props.product.whatsapp_mobile}?text=${encodedMessage}`;
    }
    return '';
}

</script>

<template>
    <Head title="Product Details" />
    <Layout>
        <div class="mx-auto max-w-[1200px]">
            <div>
                Home > Products > {{ product.name }}
            </div>
            <div class="py-6">
                <div class="flex flex-col lg:flex-row gap-6 md:gap-12">
                    <!-- Image Section -->
                    <div class="w-full lg:w-1/2">
                        <div class="grid gap-4">
                            <Carousel
                                class="relative max-w-lg mx-auto"
                                @init-api="(val) => emblaMainApi = val"
                            >
                                <CarouselContent class="flex">
                                    <CarouselItem v-for="(image, index) in media" :key="index">
                                        <img
                                            class="h-auto mx-auto w-full max-w-[100%] lg:max-w-[500px] max-h-[500px] rounded-lg object-cover object-center"
                                            :src="image.original_url"
                                            alt="Product gallery"
                                        >
                                    </CarouselItem>
                                </CarouselContent>
                                <CarouselPrevious class="hidden w-10 h-10 lg:flex bg-gray-300 justify-center" />
                                <CarouselNext class="hidden w-10 h-10 lg:flex bg-gray-300 justify-center" />
                            </Carousel>
                            <Carousel
                                class="relative w-full max-w-xs mx-auto"
                                @init-api="(val) => emblaThumbnailApi = val"
                            >
                                <CarouselContent class="flex gap-1 ml-0">
                                    <CarouselItem
                                        v-for="(image, index) in media"
                                        :key="index"
                                        class="pl-0 basis-1/4 cursor-pointer"
                                        @click="onThumbClick(index)"
                                    >
                                        <div>
                                            <img
                                                :class="index === selectedIndex ? '' : 'opacity-50'"
                                                :src="image.original_url"
                                                class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer transform transition-all duration-700"
                                                :alt="`Gallery Image ${index}`"
                                            >
                                        </div>
                                    </CarouselItem>
                                </CarouselContent>
                            </Carousel>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 flex flex-col gap-2">
                        <div class="border-b border-gray-line">
                            <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
                            <div class="flex items-center">
                                <StarRating
                                    :star-size="15"
                                    :show-rating="false"
                                    :rating="product.total_average_rating"
                                    read-only
                                    active-color="#009689"
                                    :padding="3"
                                    :key="`rating-card-${product.id}`"
                                    :increment="0.01"
                                />
                                <span class="ml-2">({{ product.reviews_count }} Reviews)</span>
                                <RateDialog :ratable="product" ratable_type="product">
                                    <a href="#" class="ml-4 text-primary font-semibold">Write a review</a>
                                </RateDialog>
                            </div>
                            <div class="py-4 border-b border-gray-line">
                                <p class="mb-2">Negotiation: <strong>{{ product.is_negotiable ? 'Negotiable' : 'Non-negotiable' }}</strong></p>
                                <p class="mb-2">Mobile number: <strong>{{ product.first_mobile }}</strong></p>
                                <p class="mb-2" v-if="product.second_mobile">Second mobile number: <strong>{{ product.second_mobile }}</strong></p>
                                <p class="mb-2">Region: <strong>{{ product.region.name }}</strong></p>
                                <p class="mb-2">Town: <strong>{{ product.town }}</strong></p>
                                <p class="mb-2" v-if="product.website">
                                    Company website: <a :href="product.website" target="_blank" class="underline hover:text-primary"><strong>visit website</strong></a>
                                </p>
                            </div>
                            <div class="text-2xl py-4 font-semibold">
                                <span class="text-sm">{{ cediSign() }}</span>
                                <span>{{ product.price }}</span>
                            </div>
                            <a
                                v-if="product.whatsapp_mobile"
                                :href="whatsappLink()"
                                target="_blank"
                            >
                                <Button class="bg-primary mb-4 border border-transparent hover:bg-transparent hover:border-primary transition-all text-white hover:text-primary font-semibold py-2 px-4 rounded-full">
                                    Chat on WhatsApp
                                </Button>
                            </a>
                        </div>
                        <ShareToSocials />
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Product Description</h3>
                            <p v-html="product.short_description"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mx-auto">
                <div class="py-12">
                    <Tabs default-value="description" class="w-full">
                        <div class="flex">
                            <TabsList class="grid grid-cols-3 gap-4 bg-transparent">
                                <TabsTrigger
                                    class="data-[state=active]:bg-transparent data-[state=active]:text-primary data-[state=active]:border-b-primary data-[state=active]:border-b-2 rounded-none data-[state=active]:shadow-none hover:border-b-primary hover:border-b-2"
                                    value="description"
                                >
                                    Description
                                </TabsTrigger>
                                <TabsTrigger
                                    class="data-[state=active]:bg-transparent data-[state=active]:text-primary data-[state=active]:border-b-primary data-[state=active]:border-b-2 rounded-none data-[state=active]:shadow-none hover:border-b-primary hover:border-b-2"
                                    value="additional-information"
                                >
                                    Additional information
                                </TabsTrigger>
                                <TabsTrigger
                                    class="data-[state=active]:bg-transparent data-[state=active]:text-primary data-[state=active]:border-b-primary data-[state=active]:border-b-2 rounded-none data-[state=active]:shadow-none hover:border-b-primary hover:border-b-2"
                                    value="reviews"
                                >
                                    Reviews ({{ product.reviews_count }})
                                </TabsTrigger>
                            </TabsList>
                        </div>

                        <TabsContent class="border-s border-secondary/20 ps-3" value="description">
                            <div class="py-6" v-html="product.description"></div>
                        </TabsContent>
                        <TabsContent class="border-s border-secondary/20 ps-3" value="additional-information">
                            <div class="py-6">
                                <p class="mb-2">Negotiation: <strong>{{ product.is_negotiable ? 'Negotiable' : 'Non-negotiable' }}</strong></p>
                                <p class="mb-2">Mobile number: <strong>{{ product.first_mobile }}</strong></p>
                                <p class="mb-2" v-if="product.second_mobile">Second mobile number: <strong>{{ product.second_mobile }}</strong></p>
                                <p class="mb-2">Region: <strong>{{ product.region.name }}</strong></p>
                                <p class="mb-2">Town: <strong>{{ product.town }}</strong></p>
                                <p class="mb-2" v-if="product.website">
                                    Company website: <a :href="product.website" target="_blank" class="underline hover:text-primary"><strong>visit website</strong></a>
                                </p>
                                <div v-if="product.video_link" class="w-full my-5">
                                    <div class="mb-3 text-lg font-semibold">Video Portfolio</div>
                                    <VideoEmbed :url="product.video_link" />
                                </div>
                                <p class="flex gap-4 items-center">
                                    <Avatar class="h-13 w-13 border">
                                        <AvatarImage :src="product.user.avatar?.original_url ?? ''" />
                                        <AvatarFallback class="bg-gray-200 uppercase">{{ product.user.initials }}</AvatarFallback>
                                    </Avatar>
                                    <span>{{ product.user.fullname }}</span>
                                </p>
                            </div>
                        </TabsContent>
                        <TabsContent class="ps-3" value="reviews">
                            <div class="py-6">
                                <div class="flex items-end justify-between mb-4">
                                    <h3 class="text-lg font-semibold">Customer Reviews</h3>
                                    <RateDialog :ratable="product" ratable_type="product">
                                        <Button>Write a review</Button>
                                    </RateDialog>
                                </div>
                                <div v-if="!product.reviews.length" class="text-xl">No reviews</div>
                                <ReviewsList v-else :ratable="product" :reviews="product.reviews" />
                            </div>
                        </TabsContent>
                    </Tabs>
                </div>
            </div>
            <div class="mt-10 hidden lg:block overflow-hidden">
                <div class="mb-4 text-lg font-semibold text-fade flex gap-2 items-center">
                    Popular Sellers
                    <Store :size="22" class="text-primary"/>
                </div>
                <AdvertisedProductsH items-class="h-screen" class=""/>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
