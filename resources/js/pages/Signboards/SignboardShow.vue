<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    Briefcase,
    MapPin,
    Building,
    PlusIcon,
    Trash2,
    Edit,
    Loader2,
    ExternalLink,
    Share2,
    Navigation,
    Zap,
    Star,
    TrendingUp,
    Rocket,
    Bel,
    ChevronDown,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import { toastError, toastSuccess } from '@/lib/helpers';
import { ref, onMounted } from 'vue';
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';

import ImageShow from '@/pages/Signboards/blocks/ImageShow.vue';

import { SignboardI, SignboardSubscriptionPlanI } from '@/types';
import InputSelect from '@/components/InputSelect.vue';
const showPlans = ref(false);
const selectedPlan = ref('');

const props = defineProps<{
    signboard: SignboardI;
    plans: SignboardSubscriptionPlanI;
    payment_status: null | 'success' | 'failed';
}>();

onMounted(() => {
    // console.log(props.signboard)
});

const showDialog = ref(false);
const isDeleting = ref(false);
const deleteSignboard = () => {
    isDeleting.value = true;
    router.delete(route('my-signboards.delete', props.signboard.id), {
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess(res.props.message);
            } else {
                toastError(res.props.message);
            }
        },
        onFinish: () => {
            isDeleting.value = false;
            showDialog.value = false;
        },
    });
};
const reviewData = [
    { label: 'Overall', rating: 4 },
    { label: 'Customer Service', rating: 5 },
    { label: 'Quality', rating: 3 },
    { label: 'Price', rating: 2 },
    { label: 'Communication', rating: 5 },
    { label: 'Speed', rating: 4 },
];

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
</script>

<template>
    <Head :title="props.signboard.landmark" />
    <Layout>
        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 to-blue-50">
            <div class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-primary via-primary to-primary">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="hero-pattern absolute inset-0"></div>
                </div>

                <div class="relative px-6 py-16 md:py-24">
                    <div class="mx-auto max-w-6xl">
                        <nav class="mb-8">
                            <div class="flex items-center space-x-2 text-sm text-white/80">
                                <span>Signboards</span>
                                <span>/</span>
                                <span>{{ props.signboard.region.name }}</span>
                                <span>/</span>
                                <span class="font-medium text-white">{{ props.signboard.landmark }}</span>
                            </div>
                        </nav>

                        <div class="flex flex-col items-start justify-between gap-8 lg:flex-row lg:items-end">
                            <div class="flex-1">
                                <div class="mb-4 flex items-center gap-4">
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-2xl border border-white/20 bg-white/10 backdrop-blur-sm"
                                    >
                                        <Briefcase class="h-8 w-8 text-white" />
                                    </div>
                                    <div>
                                        <h1 class="mb-2 text-4xl font-bold text-white md:text-5xl">
                                            {{ props.signboard.landmark }}
                                        </h1>
                                        <div class="flex items-center gap-4 text-white/90">
                                            <span class="flex items-center gap-2">
                                                <MapPin class="h-4 w-4" />
                                                {{ props.signboard.town }}
                                            </span>
                                            <span class="flex items-center gap-2">
                                                <Building class="h-4 w-4" />
                                                {{ props.signboard.region.name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="max-w-md rounded-2xl border border-white/20 bg-white/10 p-6 backdrop-blur-md">
                                    <h3 class="mb-2 font-semibold text-white">Business Information</h3>
                                    <p class="text-lg text-white/90">{{ props.signboard.business.name }}</p>
                                </div>
                            </div>

                            <div class="flex gap-3">

                                <Link
                                    :href="route('my-signboards.create')"
                                    :data="{ business: props.signboard.business.id }"
                                    class="flex items-center gap-x-2 rounded-xl border border-white/30 bg-primary px-6 py-3 text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/30"
                                >
                                    <PlusIcon class="h-5 w-5" />
                                    <span>Add Signboard</span>
                                </Link>

                                <Link
                                    :href="route('my-signboards.edit', props.signboard.slug)"
                                    class="flex items-center gap-x-2 rounded-xl border border-white/30 bg-primary px-6 py-3 text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/30"
                                >
                                    <Edit class="h-5 w-5" />
                                    <span>Edit Signboard</span>
                                </Link>

                                <button
                                    href="javascript:void(0)"
                                    variant="destructive"
                                    @click="showDialog = true"
                                    :disabled="isDeleting"
                                    class="flex items-center gap-x-2 rounded-xl border border-white/30 bg-red-500 px-6 py-3 text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-red-600"
                                >
                                    <Trash2 class="mr-2 h-4 w-4" />
                                    <span v-if="!isDeleting">Delete Signboard</span>
                                    <span v-else class="flex items-center gap-2">
                                        <Loader2 class="h-5 w-5 animate-spin" />
                                        Deleting...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 -mt-8 px-6">
                <div class="mx-auto max-w-full">
                    <div class="grid gap-8 lg:grid-cols-4">
                        <div class="lg:col-span-1">
                            <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
                                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
                                    <MapPin class="h-5 w-5 text-primary" />
                                    Location Details
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between border-b border-gray-100 py-2">
                                        <span class="font-medium text-gray-600">GPS Coordinates</span>
                                        <span class="font-mono text-sm text-gray-900">{{ props.signboard.gps }}</span>
                                    </div>
                                    <div class="flex items-center justify-between border-b border-gray-100 py-2">
                                        <span class="font-medium text-gray-600">Street</span>
                                        <span class="text-gray-900">{{ props.signboard.street || '—' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between border-b border-gray-100 py-2">
                                        <span class="font-medium text-gray-600">Block Number</span>
                                        <span class="text-gray-900">{{ props.signboard.blk_number || '—' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2">
                                        <span class="font-medium text-gray-600">Region</span>
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-gray-900 text-primary">
                                            {{ props.signboard.region.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>



                            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
                                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
                                    <Zap class="h-5 w-5 text-primary" />
                                    Quick Actions
                                </h3>
                                <div class="space-y-3">
                                    <a
                                        :href="`https://maps.google.com/?q=${props.signboard.gps}`"
                                        target="_blank"
                                        class="group flex items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"
                                    >
                                        <div class="rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110">
                                            <ExternalLink class="h-4 w-4 text-white" />
                                        </div>
                                        <span class="font-medium">View on Google Maps</span>
                                    </a>
                                    <button
                                        class="group flex w-full items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"
                                    >
                                        <div class="rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110">
                                            <Share2 class="h-4 w-4 text-white" />
                                        </div>
                                        <span class="font-medium">Share Location</span>
                                    </button>
                                    <button
                                        class="group flex w-full items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"
                                    >
                                        <div class="rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110">
                                            <Navigation class="h-4 w-4 text-white" />
                                        </div>
                                        <span class="font-medium">Get Directions</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <ImageShow
                                :featured-url="props.signboard.featured_url"
                                :gallery-urls="props.signboard.gallery_urls"
                                title="Signboard Gallery"
                            />
                        </div>

                        <div class="lg:col-span-1">
                            <div class="mb-3 rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
                                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
                                    <Zap class="h-5 w-5 text-primary" />
                                    Category
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-if="props.signboard.categories.length"
                                        v-for="category in props.signboard.categories"
                                        :key="category.id"
                                        class="rounded-full bg-primary px-3 py-1 text-sm font-medium text-white"
                                    >{{ category.name }}</span
                                    >

                                    <span v-else class="text-red rounded-full px-3 py-1 text-sm font-medium">No Category</span>
                                </div>
                            </div>


                            <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
                                <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
                                    <TrendingUp class="h-5 w-5 text-primary" />
                                    Promote Signboard
                                </h3>
                                <div class="mb-6">
                                    <div class="mb-3 flex items-center justify-between">
                                        <span class="font-medium text-gray-600">Current Status</span>
                                        <span
                                            :class="[
                                                'rounded-full px-3 py-1 text-sm font-medium',
                                                props.signboard.active_subscription > 1 ? 'bg-green-600 text-white' : 'bg-red-600 text-white',
                                            ]"
                                        >
                                            {{ props.signboard.active_subscription > 1 ? 'Promoted' : 'Not Promoted' }}
                                        </span>
                                    </div>

                                    <div class="mb-3 h-2 w-full rounded-full bg-gray-200">
                                        <div class="h-2 rounded-full bg-primary transition-all duration-300" :style="[props.signboard.views_count, '%']"></div>
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
                                        <div v-if="1 < props.signboard.active_subscription" class="flex justify-between">
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
                                        <div v-for="plan in props.plans.slice(0, 3)" :key="plan.id" class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">{{ plan.number_of_days }} Days</span>
                                            <span class="font-semibold text-primary">{{ plan.price }}</span>
                                        </div>
                                    </div>

                                    <div v-else class="relative">
                                        <InputSelect
                                            label="Select Plan"
                                            :form="subscriptionForm"
                                            model="plan_id"
                                            :options="
                                                props.plans.map((plan) => ({
                                                    label: `${plan.number_of_days} Days - ₵${plan.price}`,
                                                    value: plan.id,
                                                }))
                                            "
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
                                            <Loader2 class="h-4 w-4 animate-spin" />Processing...
                                        </span>
                                        <span v-else class="flex items-center gap-2">
                                            <Rocket class="h-4 w-4" />
                                            {{ props.signboard.active_subscription > 1 ? 'Extend Promotion' : 'Promote Now' }}</span
                                        >
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mx-auto max-w-6xl p-6">
                    <h2 class="mb-4 text-2xl font-bold text-gray-900">Reviews</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div v-for="(r, i) in reviewData" :key="i" class="rounded-lg bg-white p-4 shadow">
                            <h3 class="font-semibold text-gray-800">{{ r.label }}</h3>
                            <p class="mb-2 text-sm text-gray-600">Rated {{ r.rating }} stars out of 5</p>
                            <div class="flex">
                                <Star v-for="n in r.rating" :key="n + '-on'" class="h-5 w-5 text-yellow-500" />
                                <Star v-for="n in 5 - r.rating" :key="n + '-off'" class="h-5 w-5 text-gray-300" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ConfirmDialogue
                v-model:open="showDialog"
                :loading="isDeleting"
                title="Delete this signboard?"
                description="This action cannot be undone. All associated data and images will be permanently removed."
                confirmText="Yes, delete signboard"
                cancelText="Keep signboard"
                @confirm="deleteSignboard"
            />
        </div>
    </Layout>
</template>

<style scoped>
.hero-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3Ccircle cx='53' cy='7' r='7'/%3E%3Ccircle cx='7' cy='53' r='7'/%3E%3Ccircle cx='53' cy='53' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>
