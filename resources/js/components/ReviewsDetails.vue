<script setup lang="ts">
import ReviewsList from '@/components/review/ReviewsList.vue';
import ReviewsInsight from '@/components/review/ReviewsInsight.vue';
import { AverageRatingsI, RatableI, RatableTypesI, RatingsDistributionI, ReviewI } from '@/types';

type Props = {
    ratable: RatableI,
    ratings: AverageRatingsI
    distributions: RatingsDistributionI
    reviews: ReviewI[],
    ratable_type: RatableTypesI
}

const props = defineProps<Props>()

</script>

<template>
    <div>
        <ReviewsInsight :ratable="ratable" :ratings="ratings" :distributions="distributions" :ratable_type="ratable_type"/>
        <div class="p-4">
            <ReviewsList :ratable="ratable" :reviews="reviews" />
        </div>
    </div>
</template>

<style scoped>

</style>
