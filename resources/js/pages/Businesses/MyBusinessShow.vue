<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { Briefcase } from 'lucide-vue-next';
import MyBusinessUpdate from '@/pages/Businesses/MyBusinessUpdate.vue';
import { Button } from '@/components/ui/button';
import VerifiedBadge from '@/components/icons/VerifiedBadge.vue';
import { router } from '@inertiajs/vue3'
import { toastError, toastSuccess } from '@/lib/helpers';
import { ref } from 'vue'
const props = defineProps<{
    business: {
        id: number;
        name: string;
        email: string;
        mobile: string;
        description: string;
        facebook?: string;
        x?: string;
        linkedin?: string;
        instagram?: string;
        verified?: boolean;
        created_at?: string;
    };
}>();


const isDeleting = ref(false)

const closeBusiness = () => {
    if (!confirm('Are you sure you want to close this business?')) return

    isDeleting.value = true

    router.delete(route('my-businesses.delete', props.business.id), {
        onSuccess: (res) => {
            const message = res.props.message
            if (res.props.success) toastSuccess(message)
            else toastError(message)
        },
        onFinish: () => {
            isDeleting.value = false
        },
        preserveScroll: true,
    })
}
</script>

<template>
    <Head :title="business.name" />
    <Layout>
        <div class="bg-gray-100 min-h-screen w-full">
            <div class="h-60 bg-gradient-to-r from-orange-500 to-orange-500 relative">
                <div class="absolute left-1/2 transform -translate-x-1/2 bottom-0 translate-y-1/2">
                    <div class="h-32 w-32 rounded-full border-4 border-white bg-white shadow-lg flex items-center justify-center">
                        <Briefcase class="w-14 h-14 text-orange-500" />
                    </div>
                </div>
            </div>

            <div class="pt-20 px-6 md:px-20 w-full">
                <div class="text-center">
                    <div class="flex justify-center items-center gap-2">
                        <h1 class="text-3xl font-bold text-gray-800">{{ business.name }}</h1>
                        <VerifiedBadge :size="28" color="#1D9BF0" />
                    </div>
                    <p class="text-orange-600 font-medium text-lg">{{ business.email }}</p>
                    <p class="text-gray-500">{{ business.mobile }}</p>
                </div>


                <div class="flex justify-center gap-6 my-4">
                    <div>
                        <p class="text-gray-600 text-sm">Date Added</p>
                        <p class="font-bold text-gray-800">{{ new Date(business.created_at).toDateString() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Signboards</p>
                        <p class="font-bold text-gray-800">10</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Rating</p>
                        <p class="font-bold text-gray-800">4.9★</p>
                    </div>
                </div>
                <div class="mt-6 text-center max-w-3xl mx-auto">
                    <p class="text-gray-600 text-base leading-relaxed">
                        {{ business.description }}
                    </p>
                </div>
                <div class="flex flex-wrap justify-center gap-3 mt-8">
                    <span v-if="business.facebook" class="px-4 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">Facebook</span>
                    <span v-if="business.x" class="px-4 py-1 bg-black text-white text-sm rounded-full">X (Twitter)</span>
                    <span v-if="business.instagram" class="px-4 py-1 bg-pink-100 text-pink-800 text-sm rounded-full">Instagram</span>
                    <span v-if="business.linkedin" class="px-4 py-1 bg-blue-200 text-blue-900 text-sm rounded-full">LinkedIn</span>
                </div>
                <div class="flex flex-wrap justify-center gap-4 mt-10">
                    <MyBusinessUpdate :business="business" @updated="$inertia.reload()">
                        <Button class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded shadow">
                            Edit Business
                        </Button>
                    </MyBusinessUpdate>
                    <Button
                        :disabled="isDeleting"
                        @click="closeBusiness"
                        class="bg-red-500 text-white hover:bg-red-600 px-6 py-2 rounded-lg font-semibold transition"
                    >
                        <span v-if="!isDeleting">Close Business</span>
                        <span v-else>Closing...</span>
                    </Button>


                </div>
                <div class="flex justify-center gap-6 mt-8 text-gray-500 text-xl">
                    <a v-if="business.x" :href="business.x" target="_blank" class="hover:text-black">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a v-if="business.facebook" :href="business.facebook" target="_blank" class="hover:text-blue-600">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a v-if="business.linkedin" :href="business.linkedin" target="_blank" class="hover:text-blue-700">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a v-if="business.instagram" :href="business.instagram" target="_blank" class="hover:text-pink-600">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </Layout>
</template>
