<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import {
    Briefcase,
    MapPin,
    Building,
    PlusIcon,
    Trash2,
    Edit,
    Loader2,
    XCircle, Clock,
} from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { toastError, toastSuccess } from '@/lib/helpers';
import { ref, computed } from 'vue';
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';

import ImageShow from '@/pages/Signboards/blocks/ImageShow.vue';

import { AverageRatingsI, RatingsDistributionI, SignboardI, SignboardSubscriptionPlanI } from '@/types';
import PaymentHistory from '@/pages/Signboards/blocks/PaymentHistory.vue';
import ReviewsList from '@/components/signboard/Details/ReviewsList.vue';
import PromoteSignboard from '@/pages/Signboards/blocks/PromoteSignboard.vue';
import LocationDetails from '@/pages/Signboards/blocks/LocationDetails.vue';
import SignboardActions from '@/pages/Signboards/blocks/SignboardActions.vue';
import OperationFields from '@/pages/Signboards/blocks/OperationFields.vue';
import ReviewsDetails from '@/components/signboard/Details/ReviewsDetails.vue';
import ImagePreview from '@/components/ImagePreview.vue';



const props = defineProps<{
    signboard: SignboardI;
    plans: SignboardSubscriptionPlanI;
    ratings: AverageRatingsI;
    distributions: RatingsDistributionI;
    payment_status: null | 'success' | 'failed' | 'cancelled' | 'pending';
}>();
const reviews = computed(()=> props.signboard.reviews);

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


</script>

<template>
    <Head :title="props.signboard.landmark" />
    <Layout>
        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 to-blue-50">
            <Alert v-if="payment_status == 'pending'" class="mb-5" variant="success">
                <Clock />
                <AlertTitle>Payment in Progress</AlertTitle>
                <AlertDescription>
                    We've received your request and are currently verifying your payment. <br>
                    This may take a few moments. Once confirmed, your signboard promotion will be activated automatically.
                </AlertDescription>
            </Alert>
            <Alert v-if="payment_status == 'cancelled'" class="mb-5" variant="destructive">
                <XCircle />
                <AlertTitle>Transaction Cancelled</AlertTitle>
                <AlertDescription>
                    You cancelled the payment process. <br>
                    If this was unintentional, please try again to complete your signboard promotion.
                </AlertDescription>
            </Alert>
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
                                            {{ props.signboard.name }}
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
                            <LocationDetails :signboard="signboard"/>
                            <SignboardActions :signboard="signboard"/>
                        </div>

                        <div class="lg:col-span-2">
                            <ImagePreview
                                :featured-url="props.signboard.featured_url"
                                :gallery-urls="props.signboard.gallery_urls"
                                title="Signboard Gallery"
                            />
                        </div>

                        <div class="lg:col-span-1">
                            <OperationFields :signboard="signboard"/>
                            <PromoteSignboard :signboard="signboard" :plans="plans"/>
                        </div>
                    </div>
                </div>
                <div class="flex w-full flex-wrap mt-15">
                    <div class="w-full">
                        <h2 class="mb-4 text-2xl font-bold text-gray-900">Reviews And Comments</h2>
                    </div>
                    <div class="w-full lg:w-1/2">
                        <ReviewsDetails class="border-t-0" :signboard="signboard" :ratings="ratings" :distributions="distributions" />
                    </div>
                    <div v-if="reviews.length" class="w-full lg:border-s lg:w-1/2 lg:ps-15">
                        <h2 class="mb-4 text-lg pt-5 font-bold text-fade">Comments</h2>
                        <ReviewsList :signboard="signboard" :reviews="reviews" />
                    </div>
                </div>
                <PaymentHistory :signboard="props.signboard"/>
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
