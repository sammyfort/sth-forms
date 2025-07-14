<script setup lang="ts">

import { Loader2, Rocket, TrendingUp } from 'lucide-vue-next';
import InputSelect from '@/components/InputSelect.vue';
import { SignboardI, SignboardSubscriptionPlanI } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { number_format, toastError } from '@/lib/helpers';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    signboard: SignboardI
    plans: SignboardSubscriptionPlanI
}>();
const promotionPercentage = ref(0)
const showPlans = ref(false);
const subscriptionForm = useForm({
    plan_id: '',
    signboard_id: props.signboard.id,
});

const submitSubscriptionForm = () => {
    subscriptionForm.post(route('payments.signboard-subscription'), {
        replace: true,
        onSuccess: (response) => {
            const authorizationUrl = response.props.data.authorization_url;
            if (authorizationUrl) {
                window.location = authorizationUrl;
            } else {
                toastError('Payment initialization failed, pleased reload the page and try again');
            }
        },
    });
};

onMounted(()=>{
    const subscription = props.signboard.active_subscription
    if (subscription && subscription.total_days > 0 && subscription.days_left > 0){
        const daysPast = subscription.total_days - subscription.days_left
        if (daysPast > 0){
            promotionPercentage.value = number_format(((100 * daysPast) / subscription.total_days), 0) as unknown as number
        }
    }
    console.log(subscription)
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
                    :class="['rounded-full px-3 py-1 text-sm font-medium', props.signboard.active_subscription ? 'bg-green-600 text-white' : 'bg-red-600 text-white', ]">
                    {{ props.signboard.active_subscription ? 'Promoted' : 'Not Promoted' }}
                </span>
            </div>

            <div v-if="signboard.active_subscription" class="flex flex-col w-full gap-1 mb-3">
                <div class="text-fade ms-auto text-sm">{{ props.signboard.active_subscription?.days_left }} Day(s) Left</div>
                <div class="h-2 w-full rounded-full bg-gray-200">
                    <div class="h-2 rounded-full bg-primary transition-all duration-300" :style="{width: `${promotionPercentage}%`}">
                    </div>
                </div>
            </div>

            <div class="mb-3 h-2 w-full rounded-full bg-gray-200">
                <div class="h-2 rounded-full bg-primary transition-all duration-300"
                     :style="[props.signboard.views_count, '%']">
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
                <div v-if="props.signboard.active_subscription" class="flex justify-between">
                    <span class="text-gray-600">Expires</span>
                    <span class="font-semibold text-orange-600">
                        {{ new Date(props.signboard.active_subscription.ends_at).toDateString() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-6 mb-4 border-t border-gray-100 pt-4">
            <div class="mb-3 flex items-center justify-between">
                <h4 class="font-semibold text-gray-900">Promote Now</h4>
                <button
                    @click="showPlans = !showPlans"
                    class="text-sm font-medium text-primary transition-colors hover:text-primary/80"
                >
                    {{ showPlans ? 'Select Plan' : 'View Plans' }}
                </button>
            </div>

            <div v-if="showPlans" class="space-y-2">
                <div v-for="plan in props.plans.slice(0, 3)" :key="plan.id"
                     class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">{{ plan.number_of_days }} Days</span>
                    <span class="font-semibold text-primary">{{ plan.price }}</span>
                </div>
            </div>

            <div v-else class="relative">
                <InputSelect
                    label="Select Plan"
                    :form="subscriptionForm"
                    model="plan_id"
                    :options="props.plans.map((plan) => ({
                     label: `${plan.number_of_days} Days - ₵${plan.price}`,
                     value: plan.id,
                      }))"
                    required
                    searchable
                />
            </div>

            <button
                v-if="subscriptionForm.plan_id"
                @click="submitSubscriptionForm"
                :disabled="subscriptionForm.processing"
                class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 font-medium text-white transition-all duration-200 hover:scale-105 disabled:cursor-not-allowed disabled:opacity-50"
            >
                <span v-if="subscriptionForm.processing" class="flex items-center gap-2">
                    <Loader2 class="h-4 w-4 animate-spin" />Processing...</span>
                <span v-else class="flex items-center gap-2"><Rocket class="h-4 w-4" />
                    {{ props.signboard.active_subscription > 1 ? 'Extend Promotion' : 'Promote Now'}}
                </span>
            </button>
        </div>
    </div>

</template>

<style scoped>

</style>
