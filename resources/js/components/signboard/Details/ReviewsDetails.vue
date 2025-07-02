<script setup lang="ts">
import { AverageRatingsI, RatingsDistributionI, SignboardI } from '@/types';
import StarRating from 'vue-star-rating'
import { number_format } from '@/lib/helpers';
import { Button } from '@/components/ui/button';
import SignboardRating from '@/components/businesses/SignboardRating.vue';


type Props = {
    signboard: SignboardI,
    ratings: AverageRatingsI,
    distributions: RatingsDistributionI,
}
const props = defineProps<Props>()

</script>

<template>
    <div class="p-4 border-t text-fade">
        <div class="flex items-center">
            <div class="font-medium text-lg">Reviews</div>
            <div class="lg:ms-auto">
                <SignboardRating :signboard="signboard">
                    <Button size="sm">Add Review</Button>
                </SignboardRating>
            </div>
        </div>
        <div class="my-5 flex flex-col items-start gap-4">
            <div class="flex items-start gap-2 lg:gap-4">
                <div class="text-5xl">{{ number_format(signboard.total_average_rating, 1) }}</div>
                <StarRating
                    :star-size="37"
                    :show-rating="false"
                    :rating="signboard.total_average_rating"
                    read-only
                    active-color="#009689"
                    :padding="3"
                    class="md:w-1/3 w-full"
                    :key="`rating-card-${signboard.id}`"
                    :increment="0.01"
                />
            </div>
            <div class="">
                ({{ signboard.reviews_count }} total reviews)
            </div>
            <div class="w-full">
                <div class="text-xl mb-3 font-medium text-secondary">Insights</div>
                <div class="flex flex-wrap">
                    <div class="w-1/2">
                        <div class="font-medium mb-2">Ratings By Category</div>
                        <div class="flex flex-col gap-1 text-sm">
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.overall, 1) }}</div>
                                <div>Overall</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.customer_service, 1) }}</div>
                                <div>Customer Service</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.quality, 1) }}</div>
                                <div>Quality</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.price, 1) }}</div>
                                <div>Price</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.communication, 1) }}</div>
                                <div>Communication</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.speed, 1) }}</div>
                                <div>Speed</div>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="font-medium mb-2">Ratings Distribution</div>
                        <div class="flex flex-col gap-1 text-sm">
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">5 stars</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :class="`w-[${distributions['5']}px]`"></div>
                                </div>
                                <div>{{ distributions['5'] }}%</div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">4 stars</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :class="`w-[${distributions['4']}px]`"></div>
                                </div>
                                <div>{{ distributions['4'] }}%</div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">3 stars</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :class="`w-[${distributions['3']}px]`"></div>
                                </div>
                                <div>{{ distributions['3'] }}%</div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">2 stars</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :class="`w-[${distributions['2']}px]`"></div>
                                </div>
                                <div>{{ distributions['2'] }}%</div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">1 star</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :class="`w-[${distributions['1']}]`"></div>
                                </div>
                                <div>{{ distributions['1'] }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full flex flex-col gap-3 mt-3">
        </div>
    </div>
</template>

<style scoped>

</style>
