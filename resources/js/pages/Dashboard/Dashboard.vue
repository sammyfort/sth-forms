<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import StatsCard from '@/pages/Dashboard/blocks/StatsCard.vue';
import { Eye, PlusIcon, Rocket, ChartColumnIncreasing, ChartBarIcon } from 'lucide-vue-next';
import type { SignboardI, PromotionI } from '@/types';

const props = defineProps<{
    promotions: PromotionI[];
    signboards: SignboardI[];
}>();

</script>

<template>
    <Layout>
        <Head title="Dashboard" />

        <div class="min-h-screen w-full bg-gray-50">
            <div class="bg-white shadow-sm border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Signboard Overview</h1>
                            <p class="text-sm text-gray-600">Welcome back! Here's what's happening with your signboards.</p>
                        </div>
                        <div class="flex space-x-3">
                            <Link :href="route('my-signboards.create')"
                                  class="flex items-center gap-x-2 bg-primary backdrop-blur-sm text-white border border-white/30 px-6 py-3 rounded-xl transition-all duration-200 hover:scale-105">
                                <PlusIcon class="w-5 h-5" />
                                <span>Add Signboard</span>
                            </Link>
                            <Link :href="route('my-signboards.index')"
                                  class="flex items-center gap-x-2 bg-green-500 backdrop-blur-sm text-white border border-white/30 px-6 py-3 rounded-xl transition-all duration-200 hover:scale-105">
                                <PlusIcon class="w-5 h-5" />
                                <span>Create Campaign</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <StatsCard title="Total Signboards" :value="props.signboards.length" icon-bg="bg-primary text-white" :icon="ChartBarIcon" />
                    <StatsCard title="Total Campaigns" :value="props.promotions.length" icon-bg="bg-primary text-white" :icon="Rocket" />
                    <StatsCard title="Active Campaigns" :value="props.promotions.filter(s => s.is_active).length" icon-bg="bg-primary text-white" :icon="ChartColumnIncreasing" />
                    <StatsCard title="Total Views" :value="props.signboards.filter(s => s.reviews_count).length" icon-bg="bg-primary text-white" :icon="Eye"/>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-semibold text-gray-900">Your Signboards</h2>
                                    <Link :href="route('my-signboards.index')" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        View All
                                    </Link>
                                </div>
                            </div>
                            <div class="divide-y divide-gray-200">
                                <div v-if="props.signboards.length">
                                    <div v-for="signboard in props.signboards.slice(0, 5)" :key="signboard.id"
                                         class="p-6 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-medium text-gray-900">{{ signboard.business.name }}</h3>
                                                <p class="text-sm text-gray-600">{{ signboard.landmark }}</p>
                                                <div class="flex items-center mt-2 space-x-4">
                                                <span class="text-sm text-gray-500">
                                                    {{ signboard.reviews_count}} Views
                                                </span>
                                                    <span class="text-sm text-gray-500">
                                                    {{ signboard.reviews_count }} Visitors
                                                </span>
                                                    <span class="text-sm text-gray-500 bg-green-100 text-green-800">
                                                    {{ 'Active' }}
                                                </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                            <span
                                                :class="['px-2 py-1 rounded-full text-xs font-medium', 'bg-green-100 text-green-800']">
                                                {{ 'Active'}}
                                            </span>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-else  class="p-6 space-y-4 text-center">
                                    <h3 class="text-2xl font-semibold mb-2 text-gray-700">No Signboard yet</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Recent Campaigns</h2>
                            </div>
                            <div  v-if="promotions.length" class="p-6 space-y-4">
                                <div v-for="subscription in props.promotions" :key="subscription.id"
                                     class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-medium text-gray-900">{{ subscription.amount }}</h3>
                                        <span
                                            :class="['px-2 py-1 rounded-full text-xs font-medium',]">
                                            {{ subscription.payment_status }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ subscription?.signboard?.business?.name }}</p>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500">Budget</p>
                                            <p class="font-medium">{{ subscription.amount }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Spent</p>
                                            <p class="font-medium">{{ subscription.amount }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Impressions: 100}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else  class="p-6 space-y-4 text-center">
                                <h3 class="text-2xl font-semibold mb-2 text-gray-700">No Campaign yet</h3>
                            </div>
                        </div>

<!--                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">-->
<!--                            <div class="p-6 border-b border-gray-200">-->
<!--                                <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>-->
<!--                            </div>-->
<!--                            <div class="p-6 space-y-3">-->
<!--                                <button-->
<!--                                    class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg font-medium transition-colors text-left">-->
<!--                                    <div class="flex items-center">-->
<!--                                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6" />-->
<!--                                        </svg>-->
<!--                                        Add New Signboard-->
<!--                                    </div>-->
<!--                                </button>-->
<!--                                <button-->
<!--                                    class="w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-lg font-medium transition-colors text-left">-->
<!--                                    <div class="flex items-center">-->
<!--                                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                                                  d="M13 10V3L4 14h7v7l9-11h-7z" />-->
<!--                                        </svg>-->
<!--                                        Launch Campaign-->
<!--                                    </div>-->
<!--                                </button>-->

<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
</style>

