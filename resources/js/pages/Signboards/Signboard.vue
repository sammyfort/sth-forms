<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { AverageRatingsI, RatingsDistributionI, SignboardI } from '@/types';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import TrustedBadge from '@/components/badges/TrustedBadge.vue';
import ContactDetails from '@/components/signboard/Details/ContactDetails.vue';
import SocialsDetails from '@/components/signboard/Details/SocialsDetails.vue';
import LocationDetails from '@/components/signboard/Details/LocationDetails.vue';
import { Badge } from '@/components/ui/badge';
import ReviewsDetails from '@/components/signboard/Details/ReviewsDetails.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import StarRating from 'vue-star-rating';

type Props = {
    signboard: SignboardI;
    ratings: AverageRatingsI;
    distributions: RatingsDistributionI;
};
const props = defineProps<Props>();
const business = props.signboard.business;
const reviews = props.signboard.reviews;

</script>

<template>
    <Head title="Signboard Details" />
    <Layout>
        <div class="grid w-full grid-cols-1 lg:grid-cols-8">
            <div class="order-1 p-3 lg:order-2 lg:col-span-4">
                <div class="rounded-lg border">
                    <div class="flex items-center gap-4 rounded-t-lg border-b bg-secondary p-4 text-white">
                        <Avatar class="h-[5.5rem] w-[5.5rem]">
                            <AvatarImage src="" :size="50" />
                            <AvatarFallback class="text-2xl font-bold text-black">{{ business.initials }}</AvatarFallback>
                        </Avatar>
                        <div class="flex w-full flex-col">
                            <div class="text-2xl font-black">{{ business.name }}</div>
                            <div class="font-semibold">{{ signboard.landmark }}</div>
                            <div class="flex items-center gap-2 font-semibold">
                                <span>Trusted</span>
                                <TrustedBadge :size="20" />
                            </div>
                            <div class="text-sm text-gray-300 lg:ms-auto">{{ signboard.created_at_str }}</div>
                        </div>
                    </div>
                    <div class="text-fade p-4">
                        <div class="flex flex-wrap">
                            <div class="flex w-full flex-col gap-7 lg:w-1/2">
                                <ContactDetails :signboard="signboard" />
                                <SocialsDetails :signboard="signboard" />
                            </div>
                            <div class="w-full lg:ms-auto lg:w-1/2 lg:ps-10">
                                <LocationDetails :signboard="signboard" />
                            </div>
                        </div>
                    </div>
                    <div class="text-fade border-t p-4">
                        <div class="text-lg font-medium">Business Operation Details</div>
                        <div class="mt-3 flex w-full flex-col gap-3">
                            <div>
                                <div class="mb-1 underline">Description</div>
                                <div>{{ business.description }}</div>
                            </div>
                            <div>
                                <div class="text-fade mb-1 underline">Fields Of Operation</div>
                                <div class="flex flex-wrap gap-3">
                                    <Badge v-for="category in signboard.categories" :key="category.id" class="cursor-pointer" variant="secondary">
                                        <Link href="" class="hover:text-secondary">{{ category.name }}</Link>
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ReviewsDetails :signboard="signboard" :ratings="ratings" :distributions="distributions" />
                    <div class="p-4">
                        <Card v-for="review in reviews" :key="`review-${review.id}`" class="gap-4 shadow-none">
                            <CardHeader class="flex items-center">
                                <CardTitle class="text-fade">{{ review.user.fullname }}</CardTitle>
                                <Avatar class="h-12 w-12 lg:ms-auto">
                                    <AvatarImage :src="review.user.avatar?.original_url ?? ''" />
                                    <AvatarFallback>{{ review.user.initials }}</AvatarFallback>
                                </Avatar>
                            </CardHeader>
                            <CardContent>
                                <div class="w-full text-sm">
                                    {{ review.review }}
                                </div>
                                <div class="text-fade mt-3 flex w-full items-center">
                                    <div class="text-xs">{{ review.created_at_str }}</div>
                                    <div class="ms-auto">
                                        <StarRating
                                            :star-size="15"
                                            :show-rating="false"
                                            :rating="review.average_rating"
                                            read-only
                                            active-color="#009689"
                                            :padding="3"
                                            class="w-full md:w-1/3"
                                            :key="`rating-card-${signboard.id}`"
                                            :increment="0.01"
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
            <div class="order-2 p-3 lg:order-1 lg:col-span-2">rating details</div>
            <div class="order-3 p-3 lg:order-3 lg:col-span-2">advertised signboards</div>
        </div>
    </Layout>
</template>

<style scoped></style>
