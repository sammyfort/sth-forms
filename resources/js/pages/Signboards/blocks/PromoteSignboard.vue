<script setup lang="ts">
import { TrendingUp } from 'lucide-vue-next';
import { PromotionPlanI, SignboardI } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { PromotableE } from '@/lib/enums';
import PromoteNow from '@/components/promotions/PromoteNow.vue';
import ActivePromotionInfo from '@/pages/Products/ActivePromotionInfo.vue';

const props = defineProps<{
    signboard: SignboardI
    plans: PromotionPlanI[],
    promotableType: PromotableE
}>();

useForm({
    plan_id: null,
    promotable_id: props.signboard.id,
    promotable_type: props.promotableType
});

</script>

<template>
    <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
        <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
            <TrendingUp class="h-5 w-5 text-primary" />
            Promote Signboard
        </h3>
        <div class="mb-6">
            <ActivePromotionInfo :promotable="signboard"/>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Reviews</span>
                    <span class="font-semibold">{{ props.signboard.reviews_count || 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Views</span>
                    <span class="font-semibold">{{ props.signboard.views_count || 0 }}</span>
                </div>
            </div>
        </div>
        <PromoteNow :promotable="signboard" :plans="plans" :promotable-type="promotableType" />
    </div>

</template>

<style scoped>

</style>
