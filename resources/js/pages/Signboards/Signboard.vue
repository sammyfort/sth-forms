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


type Props = {
    signboard: SignboardI,
    ratings: AverageRatingsI,
    distributions: RatingsDistributionI
}
const props = defineProps<Props>()
const business = props.signboard.business

</script>

<template>
    <Head title="Signboard Details" />
    <Layout>
        <div class="w-full grid grid-cols-1 lg:grid-cols-8">
            <div class="p-3 order-1 lg:order-2 lg:col-span-4">
                <div class="border rounded-lg">
                    <div class="p-4 flex items-center gap-4 border-b bg-secondary text-white rounded-t-lg">
                        <Avatar class="w-[5.5rem] h-[5.5rem]">
                            <AvatarImage src="" :size="50"/>
                            <AvatarFallback class="text-black text-2xl font-bold">TC</AvatarFallback>
                        </Avatar>
                        <div class="flex flex-col w-full">
                            <div class="text-2xl font-black">{{ business.name }}</div>
                            <div class="font-semibold">{{ signboard.landmark }}</div>
                            <div class="font-semibold flex items-center gap-2">
                                <span>Trusted</span>
                                <TrustedBadge :size="20"/>
                            </div>
                            <div class="lg:ms-auto text-gray-300 text-sm">{{ signboard.created_at_str }}</div>
                        </div>
                    </div>
                    <div class="p-4 text-fade">
                        <div class="flex flex-wrap">
                            <div class="w-full lg:w-1/2 flex flex-col gap-7">
                                <ContactDetails :signboard="signboard"/>
                                <SocialsDetails :signboard="signboard"/>
                            </div>
                            <div class="lg:ms-auto w-full lg:w-1/2 lg:ps-10">
                                <LocationDetails :signboard="signboard"/>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border-t text-fade">
                        <div class="font-medium text-lg">Business Operation Details</div>
                        <div class="w-full flex flex-col gap-3 mt-3">
                            <div>
                                <div class="underline mb-1">Description</div>
                                <div>{{ business.description }}</div>
                            </div>
                            <div>
                                <div class="text-fade underline mb-1">Field Of Operation</div>
                                <div class="flex flex-wrap gap-3">
                                    <Badge
                                        v-for="category in signboard.categories"
                                        :key="category.id"
                                        class="p-1.5 cursor-pointer"
                                    >
                                        <Link href="" class="hover:text-secondary">{{ category.name }}</Link>
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ReviewsDetails
                        :signboard="signboard"
                        :ratings="ratings"
                        :distributions="distributions"
                    />
                </div>
            </div>
            <div class="p-3 order-2 lg:order-1 lg:col-span-2">rating details</div>
            <div class="p-3 order-3 lg:order-3 lg:col-span-2">advertised signboards</div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
