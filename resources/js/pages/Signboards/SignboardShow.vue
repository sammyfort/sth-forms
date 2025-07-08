<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Briefcase, MapPin, Building, PlusIcon, Trash2, Edit, Loader2, ExternalLink, Share2, Navigation, Zap, Star, CheckCheck, AlertCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import { toastError, toastSuccess } from '@/lib/helpers';
import { ref, onMounted } from 'vue';
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';

import ImageShow from '@/pages/Signboards/blocks/ImageShow.vue';

import { SignboardI } from '@/types';
import SignboardGallery from '@/components/signboard/Details/SignboardGallery.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';



const props = defineProps<{
    signboard: SignboardI,
    payment_status: null|"success"|"failed",
}>();

onMounted( () => {
   // console.log(props.signboard)
})

const showDialog = ref(false);
const isDeleting = ref(false);
const deleteSignboard = () => {
    isDeleting.value = true;
    router.delete(route('my-signboards.delete', props.signboard.id), {
        onSuccess: res => {
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
    'plan_id': 1,
    'signboard_id': props.signboard.id
})

const submitSubscriptionForm = ()=>{
    subscriptionForm.post(route('payments.signboard-subscription'), {
        replace: true,
        onSuccess: (response)=>{
            const authorizationUrl = response.props.data.authorization_url
            if (authorizationUrl){
                window.location = authorizationUrl
            }
            else {
                toastError("Payment initialization failed, pleased reload the page and try again")
            }
        }
    });
}

</script>
<template>
    <Head :title="props.signboard.landmark" />
    <Layout>
        <div class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen w-full">
            <Alert v-if="payment_status" class="mb-5" :variant="payment_status=='success' ? 'success' : 'destructive' ">
                <CheckCheck v-if="payment_status === 'success' "/>
                <AlertCircle v-else/>
                <AlertTitle>Payment Status</AlertTitle>
                <AlertDescription>
                    <span v-if="payment_status === 'success' ">
                        Your subscription is active. Your signboard is now being promoted and will enjoy increased visibility
                        across our platform. Sit back and watch the attention roll in!
                    </span>
                    <span v-else>
                        Unfortunately, your payment couldn’t be processed. Please check your payment details and try again. If the issue persists, contact support for assistance.
                    </span>
                </AlertDescription>
            </Alert>
            <div class="relative overflow-hidden">
                <div  class="absolute inset-0 bg-gradient-to-r from-primary via-primary to-primary">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute inset-0 hero-pattern"></div>
                </div>

                <div class="relative px-6 py-16 md:py-24">
                    <div class="max-w-6xl mx-auto">

                        <nav class="mb-8">
                            <div class="flex items-center space-x-2 text-white/80 text-sm">
                                <span>Signboards</span>
                                <span>/</span>
                                <span>{{ props.signboard.region.name }}</span>
                                <span>/</span>
                                <span class="text-white font-medium">{{ props.signboard.landmark }}</span>
                            </div>
                        </nav>

                        <div class="flex flex-col lg:flex-row items-start lg:items-end justify-between gap-8">

                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="h-16 w-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center">
                                        <Briefcase class="w-8 h-8 text-white" />
                                    </div>
                                    <div>
                                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                                            {{ props.signboard.landmark }}
                                        </h1>
                                        <div class="flex items-center gap-4 text-white/90">
                                            <span class="flex items-center gap-2">
                                                <MapPin class="w-4 h-4" />
                                                {{ props.signboard.town }}
                                            </span>
                                            <span class="flex items-center gap-2">
                                                <Building class="w-4 h-4" />
                                                {{ props.signboard.region.name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-6 max-w-md">
                                    <h3 class="text-white font-semibold mb-2">Business Information</h3>
                                    <p class="text-white/90 text-lg">{{ props.signboard.business.name }}</p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <Button @click="submitSubscriptionForm" :processing="subscriptionForm.processing">
                                    Subscribe
                                </Button>

                                <Link :href="route('my-signboards.create')"
                                      :data="{ business: props.signboard.business.id }"
                                    class="flex items-center gap-x-2 bg-primary hover:bg-primary/30 backdrop-blur-sm text-white border border-white/30 px-6 py-3 rounded-xl transition-all duration-200 hover:scale-105">
                                    <PlusIcon class="w-5 h-5" />
                                    <span>Add Signboard</span>
                                </Link>

                                <Link :href="route('my-signboards.edit', props.signboard.slug)"
                                      class="flex items-center gap-x-2 bg-primary hover:bg-primary/30 backdrop-blur-sm text-white border border-white/30 px-6 py-3 rounded-xl transition-all duration-200 hover:scale-105">
                                    <Edit class="w-5 h-5" />
                                    <span>Edit Signboard</span>
                                </Link>



                                <Button
                                    variant="destructive"
                                    @click="showDialog = true"
                                    class="bg-red-500/20 hover:bg-red-500 backdrop-blur-sm text-white border border-red-300/30 px-6 py-3 rounded-xl transition-all duration-200 hover:scale-105"
                                    :disabled="isDeleting"
                                >
                                    <Trash2 class="w-4 h-4 mr-2" />
                                    <span v-if="!isDeleting">Delete</span>
                                    <span v-else class="flex items-center gap-2">
                                        <Loader2 class="w-4 h-4 animate-spin" />
                                        Deleting...
                                    </span>
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="px-6 -mt-8 relative z-10">
                <div class="max-w-6xl mx-auto">
                    <div class="grid lg:grid-cols-3 gap-8">

                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <MapPin class="w-5 h-5 text-primary" />
                                    Location Details
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">GPS Coordinates</span>
                                        <span class="text-gray-900 font-mono text-sm">{{ props.signboard.gps }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Street</span>
                                        <span class="text-gray-900">{{ props.signboard.street || '—' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Block Number</span>
                                        <span class="text-gray-900">{{ props.signboard.blk_number || '—' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600 font-medium">Region</span>
                                        <span class="text-gray-900 bg-blue-100 text-primary px-3 py-1 rounded-full text-sm font-medium">
                                            {{ props.signboard.region.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-3">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <Zap class="w-5 h-5 text-primary" />
                                    Category
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <span v-if="props.signboard.categories.length"
                                          v-for="category in props.signboard.categories"
                                          :key="category.id"
                                          class="bg-primary text-white px-3 py-1
                                          rounded-full text-sm font-medium">{{ category.name }}</span>

                                    <span v-else
                                          class="text-red px-3 py-1
                                          rounded-full text-sm font-medium">No Category</span>
                                </div>
                            </div>


                            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <Zap class="w-5 h-5 text-primary" />
                                    Quick Actions
                                </h3>
                                <div class="space-y-3">
                                    <a
                                        :href="`https://maps.google.com/?q=${props.signboard.gps}`"
                                        target="_blank"
                                        class="flex items-center gap-3 p-3 rounded-xl bg-orange-50 hover:bg-blue-100   text-primary transition-colors duration-200 group"
                                    >
                                        <div class="p-2 bg-primary rounded-lg group-hover:scale-110 transition-transform duration-200">
                                            <ExternalLink class="w-4 h-4 text-white" />
                                        </div>
                                        <span class="font-medium">View on Google Maps</span>
                                    </a>
                                    <button class="flex items-center gap-3 p-3 rounded-xl bg-orange-50 hover:bg-blue-100 text-primary transition-colors duration-200 group w-full">
                                        <div class="p-2 bg-primary rounded-lg group-hover:scale-110 transition-transform duration-200">
                                            <Share2 class="w-4 h-4 text-white" />
                                        </div>
                                        <span class="font-medium">Share Location</span>
                                    </button>
                                    <button class="flex items-center gap-3 p-3 rounded-xl bg-orange-50 hover:bg-blue-100  text-primary transition-colors duration-200 group w-full">
                                        <div class="p-2 bg-primary rounded-lg group-hover:scale-110 transition-transform duration-200">
                                            <Navigation class="w-4 h-4 text-white" />
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


                    </div>
                </div>

                <div class="max-w-6xl mx-auto p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Reviews</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="(r, i) in reviewData" :key="i" class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-semibold text-gray-800">{{ r.label }}</h3>
                            <p class="text-sm text-gray-600 mb-2">Rated {{ r.rating }} stars out of 5</p>
                            <div class="flex">
                                <Star v-for="n in r.rating" :key="n + '-on'" class="w-5 h-5 text-yellow-500" />
                                <Star v-for="n in (5 - r.rating)" :key="n + '-off'" class="w-5 h-5 text-gray-300" />
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
