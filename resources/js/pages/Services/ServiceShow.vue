<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import Layout from '@/layouts/Layout.vue'

import {
    ArrowLeft,
    Edit,
    Trash2,
    Eye,
    Hammer,
    MapPin,
    Star,
    Clock,
    DollarSign,
    Users,
    MessageCircle,
    Share2,
    Heart,
    Award,
    CheckCircle,
    PlusIcon
} from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { ServiceI } from '@/types'
import { ref, onMounted } from 'vue'
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';
import ImagePreview from '@/components/ImagePreview.vue';
import { toastSuccess } from '@/lib/helpers';

const props = defineProps<{
    service: ServiceI
}>()

onMounted(()=> {
    console.log(props.service.categories)
})

const isDeleting = ref(false)

const handleDelete = () => {

        isDeleting.value = true
        router.delete(route('my-services.destroy', props.service.id), {
            onFinish: () => {
                isDeleting.value = false
            }
        })

}

const handleShare = () => {
    if (navigator.share) {
        navigator.share({
            title: props.service.title,
            text: props.service.description,
            url: window.location.href
        })
    } else {
        navigator.clipboard.writeText(window.location.href)
        toastSuccess('Link copied to clipboard!');
    }
}
</script>

<template>
    <Head :title="service.title" />

    <Layout>

        <div class="hidden md:block fixed bottom-8 right-8 z-50 space-y-4">
            <Link :href="route('my-services.edit', service.id)">
                <Button class="bg-primary hover:bg-primary-700 text-white font-semibold p-4 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 ease-out">
                    <Edit class="w-5 h-5" />
                </Button>
            </Link>
            <Button
                @click="handleShare"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold p-4 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 ease-out">
                <Share2 class="w-5 h-5" />
            </Button>
        </div>

        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 via-white to-blue-50">


            <div class="relative overflow-hidden bg-secondary">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative px-6 py-8">
                    <div class="max-w-6xl mx-auto">
                        <Link :href="route('my-services.index')"
                              class="inline-flex items-center gap-2 text-white/80 hover:text-white mb-6 transition-colors">
                            <ArrowLeft class="w-5 h-5" />
                            <span>Back to My Works</span>
                        </Link>

                        <div class="flex flex-col lg:flex-row gap-8 items-start">
                            <div class="flex-shrink-0">
                                <div class="relative overflow-hidden w-80 h-64 bg-gradient-to-br from-slate-200 to-slate-300 rounded-2xl">
                                    <div class="relative w-full h-full">
                                        <img :src="service.featured" alt="Service image" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                                    </div>

                                    <div  class="absolute top-4 left-4 z-20">
                                        <span class="bg-yellow-400/90 backdrop-blur-sm text-yellow-900 text-sm font-bold px-3 py-2 rounded-full flex items-center gap-1">
                                            <Star class="w-4 h-4 fill-current" />
                                            {{service.title}}
                                        </span>
                                    </div>

                                </div>
                            </div>

                            <div class="flex-grow">
                                <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight leading-tight">
                                    {{ service.title }}
                                </h1>

                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold">
                                            {{ service.user?.fullname.charAt(0) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">{{ service.user?.fullname }}</p>
                                            <div class="flex items-center gap-1">
                                                <MapPin class="w-4 h-4 text-slate-300" />
                                                <p class="text-slate-300">{{ service.town }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                                        <Star class="w-6 h-6 text-yellow-400 fill-current mx-auto mb-2" />
                                        <div class="text-white font-bold text-lg">{{ service.rating || '4.8' }}</div>
                                        <div class="text-slate-300 text-sm">Rating</div>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                                        <MessageCircle class="w-6 h-6 text-blue-400 mx-auto mb-2" />
                                        <div class="text-white font-bold text-lg">{{ service.reviews_count || '12' }}</div>
                                        <div class="text-slate-300 text-sm">Reviews</div>
                                    </div>

                                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                                        <Eye class="w-6 h-6 text-purple-400 mx-auto mb-2" />
                                        <div class="text-white font-bold text-lg">{{ service.views_count || '245' }}</div>
                                        <div class="text-slate-300 text-sm">Views</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="md:hidden px-6 py-6 bg-white border-b border-slate-200">
                <div class="flex gap-3">
                    <Link :href="route('my-services.edit', service.id)" class="flex-1">
                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold px-6 py-4 rounded-xl shadow-lg flex items-center justify-center gap-3">
                            <Edit class="w-5 h-5" />
                            <span>Edit Service</span>
                        </Button>
                    </Link>

                    <Button @click="handleShare" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-4 rounded-xl shadow-lg">
                        <Share2 class="w-5 h-5" />
                    </Button>
                </div>
            </div>


            <div class="px-6 py-12">
                <div class="max-w-6xl mx-auto">
                    <div class="grid lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-8">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                                    <Award class="w-6 h-6 text-primary" />
                                    Service Description
                                </h2>
                                <div class="prose prose-slate max-w-none">
                                    <p class="text-slate-700 leading-relaxed text-lg">
                                        {{ service.description }}
                                    </p>

                                </div>
                            </div>


                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-2xl font-bold text-slate-900 mb-6">Field of Service</h2>
                                <div class="flex flex-wrap gap-3">
                                    <span v-for="category in service.categories"
                                          :key="category?.id"
                                          class="bg-gradient-to-r from-primary/10 to-primary/5
                                           text-primary text-sm font-semibold px-4 py-2 rounded-full border border-primary/20">
                                        {{ category?.name }}
                                    </span>
                                </div>
                            </div>

                            <div  >
                                <h2 class="text-2xl font-bold text-slate-900 mb-6">Gallery</h2>

                                     <ImagePreview :featured-url="service.featured" :gallery-urls="service.gallery"/>

                            </div>


<!--                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">-->
<!--                                <h2 class="text-2xl font-bold text-slate-900 mb-6">Performance Overview</h2>-->
<!--                                <div class="grid md:grid-cols-2 gap-6">-->
<!--                                    <div class="space-y-4">-->
<!--                                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">-->
<!--                                            <div class="flex items-center gap-3">-->
<!--                                                <CheckCircle class="w-6 h-6 text-green-600" />-->
<!--                                                <span class="font-semibold text-slate-900">Orders Completed</span>-->
<!--                                            </div>-->
<!--                                            <span class="font-bold text-green-600 text-lg">{{ service.orders_completed || '24' }}</span>-->
<!--                                        </div>-->
<!--                                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">-->
<!--                                            <div class="flex items-center gap-3">-->
<!--                                                <Users class="w-6 h-6 text-blue-600" />-->
<!--                                                <span class="font-semibold text-slate-900">Active Orders</span>-->
<!--                                            </div>-->
<!--                                            <span class="font-bold text-blue-600 text-lg">{{ service.active_orders || '3' }}</span>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="space-y-4">-->
<!--                                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl">-->
<!--                                            <div class="flex items-center gap-3">-->
<!--                                                <DollarSign class="w-6 h-6 text-yellow-600" />-->
<!--                                                <span class="font-semibold text-slate-900">Total Earnings</span>-->
<!--                                            </div>-->
<!--                                            <span class="font-bold text-yellow-600 text-lg">${{ service.total_earnings || '2,400' }}</span>-->
<!--                                        </div>-->
<!--                                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">-->
<!--                                            <div class="flex items-center gap-3">-->
<!--                                                <Heart class="w-6 h-6 text-purple-600" />-->
<!--                                                <span class="font-semibold text-slate-900">Favorites</span>-->
<!--                                            </div>-->
<!--                                            <span class="font-bold text-purple-600 text-lg">{{ service.favorites_count || '18' }}</span>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
                        </div>


                        <div class="space-y-6">


                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                                <h3 class="text-xl font-bold text-slate-900 mb-6">Service Details</h3>

                                <div class="space-y-4">


                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Status</span>
                                        <span class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">
                                            Active
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Created</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ new Date(service.created_at).toLocaleDateString() }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3">
                                        <span class="text-slate-600">Last Updated</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ new Date(service.updated_at).toLocaleDateString() }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-8 space-y-3">
                                    <Link :href="route('my-services.edit', service.id)" class="block">
                                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                            <Edit class="w-4 h-4" />
                                            Edit Service
                                        </Button>
                                    </Link>

                                    <Link :href="route('my-services.create')" class="block">
                                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                            <PlusIcon class="w-4 h-4" />
                                            Add Service
                                        </Button>
                                    </Link>

                                    <ConfirmDialogue @confirm="handleDelete">
                                        <Button
                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3
                                            px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                            <Trash2 class="w-4 h-4" />
                                            {{ isDeleting ? 'Deleting...' : 'Delete Work' }}
                                        </Button>
                                    </ConfirmDialogue>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
