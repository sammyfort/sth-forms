<script setup lang="ts">
import ReviewsList from '@/components/signboard/Details/ReviewsList.vue';
import ReviewsInsight from '@/components/signboard/Details/ReviewsInsight.vue';
import { AverageRatingsI, RatableItemsI, RatingsDistributionI, ReviewI } from '@/types';

type Props = {
    ratable: RatableItemsI,
    ratings: AverageRatingsI
    distributions: RatingsDistributionI
    reviews: ReviewI[]
}

const props = defineProps<Props>()

</script>

<template>
    <div>
        <ReviewsInsight :signboard="ratable" :ratings="ratings" :distributions="distributions" />
        <div class="p-4">
            <ReviewsList :signboard="ratable" :reviews="reviews" />
        </div>
    </div>
</template>

<style scoped>

</style>
