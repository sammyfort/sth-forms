<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { Briefcase } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3'
import { toastError, toastSuccess } from '@/lib/helpers';
import { onMounted, ref } from 'vue';
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';
import SignboardEdit from '@/pages/Signboards/SignboardEdit.vue';
import { getApi } from '@/lib/api';
const props = defineProps<{
    signboard: {
        id: number;
        slug: string;
        town: string;
        street: string;
        landmark: string;
        blk_number: string;
        gps: string;
        business_id: number;
        region_id: number;
    };


}>();
const regions = ref([])
const businesses = ref([])

onMounted(async () => {
    const [regRes, busRes] = await Promise.all([
        getApi('regions'),
        getApi('authBusinesses')
    ])

    regions.value = regRes.metadata?.regions ?? []
    businesses.value = busRes.metadata?.businesses ?? []

   // console.log(props.signboard)
})

const showDialog = ref(false)
const isDeleting = ref(false)

const deleteSignboard = () => {
    isDeleting.value = true

    router.delete(route('my-signboards.delete', props.signboard.id), {
        onSuccess: (res) => {
            const message = res.props.message
            if (res.props.success) toastSuccess(message)
            else toastError(message)
        },
        onFinish: () => {
            isDeleting.value = false
            showDialog.value = false
        },
        preserveScroll: true,
    })
}
</script>

<template>
    <Head :title="signboard.town" />
    <Layout>
        <div class="bg-gray-100 min-h-screen w-full">
            <div class="h-60 bg-gradient-to-r from-orange-500 to-yellow-400 relative">
                <div class="absolute left-6 bottom-6">
                    <div class="h-20 w-20 rounded-full border-4 border-white bg-white shadow-lg flex items-center justify-center">
                        <Briefcase class="w-10 h-10 text-orange-500" />
                    </div>
                </div>
                <div class="absolute bottom-6 right-6 text-white">
                    <h1 class="text-2xl font-bold">Signboard Details</h1>
                    <p class="text-sm">Detailed view with location and metadata</p>
                </div>
            </div>
            <div class="pt-10 px-6 md:px-20 w-full max-w-5xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ signboard.landmark }}</h2>
                            <p class="text-orange-600">Town: {{ signboard.town }}</p>
                        </div>
                        <a :href="`https://maps.google.com/?q=${signboard.gps}`" target="_blank"
                           class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5 14.5 7.62 14.5 9 13.38 11.5 12 11.5z"
                                />
                            </svg>
                            View on Map
                        </a>

                        <SignboardEdit
                            :signboard="signboard"
                            :businesses="businesses"
                            :regions="regions"
                            @updated="$inertia.reload()">
                            <Button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                                Edit Business
                            </Button>
                        </SignboardEdit>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-6 mt-6 text-gray-700 mb-3">
                        <div>
                            <p class="text-sm font-medium">Region</p>
                            <p class="font-semibold">{{ signboard.region.name || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Street</p>
                            <p class="font-semibold">{{ signboard.street || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Block Number</p>
                            <p class="font-semibold">{{ signboard.blk_number || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium">GPS Coordinates</p>
                            <p class="font-semibold">{{ signboard.gps }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Slug</p>
                            <p class="font-semibold">{{ signboard.slug }}</p>
                        </div>
                    </div>

                    <Button variant="destructive" @click="showDialog = true"
                            class="bg-red-500 text-white hover:bg-red-600 px-6 py-2 rounded-lg font-semibold transition"
                    >
                        <span v-if="!isDeleting">Delete Signboard</span>
                        <span v-else>Closing...</span>
                    </Button>

                    <ConfirmDialogue
                        v-model:open="showDialog"
                        :loading="isDeleting"
                        title="Delete this signboard?"
                        description="This will permanently remove the signboard. Are you sure?"
                        confirmText="Yes, Close it"
                        cancelText="Cancel"
                        @confirm="deleteSignboard"
                    />
                </div>

            </div>


        </div>
    </Layout>
</template>
