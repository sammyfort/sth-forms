<script setup lang="ts">

import { JobI, ProductI, ServiceI, SignboardI } from '@/types';
import { onMounted, ref } from 'vue';
import { number_format } from '@/lib/helpers';

const promotionPercentage = ref(0)

const props = defineProps<{
    promotable: ServiceI|SignboardI|JobI|ProductI
}>()

onMounted(()=>{
    const promotion = props.promotable.active_promotion
    if (promotion && promotion.total_days > 0 && promotion.days_left > 0){
        const daysPast = promotion.total_days - promotion.days_left
        if (daysPast > 0){
            promotionPercentage.value = number_format(((100 * daysPast) / promotion.total_days), 0) as unknown as number
        }
    }
})

</script>

<template>
    <div v-if="promotable.active_promotion" class="flex flex-col w-full gap-1 mb-3">
        <div class="mb-3 flex items-center justify-between">
            <span class="font-semibold text-gray-600">Current Status</span>
            <span
                :class="['rounded-full px-3 py-1 text-sm font-medium', promotable.active_promotion ? 'bg-green-600 text-white' : 'bg-red-600 text-white', ]">
                    {{ promotable.active_promotion ? 'Promoted' : 'Not Promoted' }}
                </span>
        </div>
        <div class="text-fade ms-auto text-sm">{{ promotable.active_promotion?.days_left }} Day(s) Left</div>
        <div class="h-2 w-full rounded-full bg-gray-200">
            <div class="h-2 rounded-full bg-primary transition-all duration-300" :style="{width: `${promotionPercentage}%`}">
            </div>
        </div>
        <div v-if="promotable.active_promotion" class="flex justify-between">
            <span class="text-gray-600">Expires</span>
            <span class="font-semibold text-orange-600 text-xs">
                {{ new Date(promotable.active_promotion.ends_at).toDateString() }}
            </span>
        </div>
    </div>
</template>

<style scoped>

</style>
