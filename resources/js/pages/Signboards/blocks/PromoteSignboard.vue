<script setup lang="ts">

import { TrendingUp } from 'lucide-vue-next';
import { PromotionPlanI, SignboardI } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { number_format } from '@/lib/helpers';
import { onMounted, ref } from 'vue';
import { PromotableE } from '@/lib/enums';
import PromoteNow from '@/components/promotions/PromoteNow.vue';

const props = defineProps<{
    signboard: SignboardI
    plans: PromotionPlanI[],
    promotableType: PromotableE
}>();

const promotionPercentage = ref(0)

useForm({
    plan_id: null,
    promotable_id: props.signboard.id,
    promotable_type: props.promotableType
});

onMounted(()=>{
    const promotion = props.signboard.active_promotion
    if (promotion && promotion.total_days > 0 && promotion.days_left > 0){
        const daysPast = promotion.total_days - promotion.days_left
        if (daysPast > 0){
            promotionPercentage.value = number_format(((100 * daysPast) / promotion.total_days), 0) as unknown as number
        }
    }
})

</script>

<template>
    <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
        <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
            <TrendingUp class="h-5 w-5 text-primary" />
            Promote Signboard
        </h3>
        <div class="mb-6">
            <div class="mb-3 flex items-center justify-between">
                <span class="font-medium text-gray-600">Current Status</span>
                <span
                    :class="['rounded-full px-3 py-1 text-sm font-medium', props.signboard.active_promotion ? 'bg-green-600 text-white' : 'bg-red-600 text-white', ]">
                    {{ props.signboard.active_promotion ? 'Promoted' : 'Not Promoted' }}
                </span>
            </div>

            <div v-if="signboard.active_promotion" class="flex flex-col w-full gap-1 mb-3">
                <div class="text-fade ms-auto text-sm">{{ props.signboard.active_promotion?.days_left }} Day(s) Left</div>
                <div class="h-2 w-full rounded-full bg-gray-200">
                    <div class="h-2 rounded-full bg-primary transition-all duration-300" :style="{width: `${promotionPercentage}%`}">
                    </div>
                </div>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Reviews</span>
                    <span class="font-semibold">{{ props.signboard.reviews_count || 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Views</span>
                    <span class="font-semibold">{{ props.signboard.views_count || 0 }}</span>
                </div>
                <div v-if="props.signboard.active_promotion" class="flex justify-between">
                    <span class="text-gray-600">Expires</span>
                    <span class="font-semibold text-orange-600">
                        {{ new Date(props.signboard.active_promotion.ends_at).toDateString() }}
                    </span>
                </div>
            </div>
        </div>
        <PromoteNow :promotable="signboard" :plans="plans" :promotable-type="promotableType" />
    </div>

</template>

<style scoped>

</style>
