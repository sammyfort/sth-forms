<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { ReviewI, SignboardI } from '@/types';
import StarRating from 'vue-star-rating';

type Props = {
    signboard: SignboardI
    reviews: ReviewI[]
}
defineProps<Props>()

</script>

<template>
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
</template>

<style scoped>

</style>
