<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { PlusIcon, Briefcase} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import SignboardCreate from '@/pages/Signboards/SignboardCreate.vue';
import { onMounted, ref } from 'vue';
import { getApi } from '@/lib/api';


const props = defineProps<{
    signboards: Array<{
        id: number;
        slug: string;
        town: string;
        street: string;
        landmark: string;
        blk_number: string;
        gps: string;
    }>;
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
})

</script>

<template>
    <Head title="My Signboard" />

    <Layout>
        <div class="relative min-h-screen px-4 pt-8">
            <div class="flex justify-end mb-4">
                <SignboardCreate
                    :businesses="businesses"
                    :regions="regions"
                    @created="$inertia.reload({ only: ['signboards'] })">
                    <Button
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded shadow flex items-center gap-2"
                    >
                        <PlusIcon class="w-4 h-4" />
                        <span>Add Signboard</span>
                    </Button>
                </SignboardCreate>
            </div>

            <div v-if="signboards.length" class="mt-4 flex flex-wrap items-center justify-center">
                <div class="mx-auto max-w-full">
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                        <Link  v-for="signboard in signboards"
                            :key="signboard.id"
                            :href="route('my-signboards.show', signboard.slug)">
                            <div
                                class="relative rounded-2xl bg-gradient-to-t from-blue-300 to-white p-0.5 shadow-[0_0px_20px_0px_rgba(0,0,0,0.1)] transition-all duration-300 ease-in-out hover:shadow-[0_0px_25px_0px_rgba(0,0,0,0.2)]">
                                <div class="relative rounded-2xl p-6 bg-white text-center h-full flex flex-col justify-between hover:translate-y-[-5px] transition duration-300 ease-in-out overflow-hidden">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="absolute top-4 left-4 w-48 h-48 text-blue-100 opacity-50 z-0 pointer-events-none transform transition-all duration-500 group-hover:-translate-y-4 group-hover:-translate-x-4"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"
                                        />
                                    </svg>

                                    <div class="relative z-10">
                                        <h3 class="text-lg font-bold text-gray-800 truncate">
                                            {{ signboard.business?.name  }}
                                        </h3>
                                        <p class="text-xs text-blue-600 mb-2">
                                            {{ signboard.region?.name  }}
                                        </p>

                                        <p class="text-xl font-semibold text-orange-500">
                                            {{ signboard.landmark }}
                                        </p>
                                        <div class="mt-4 text-sm text-gray-700 space-y-1">
                                            <p><strong>Town:</strong> {{ signboard.town }}</p>
                                            <p v-if="signboard.street"><strong>Street:</strong> {{ signboard.street }}</p>
                                            <p v-if="signboard.blk_number"><strong>Block:</strong> {{ signboard.blk_number }}</p>
                                            <p><strong>GPS:</strong> {{ signboard.gps }}</p>
                                        </div>
                                    </div>

                                    <a :href="`https://maps.google.com/?q=${signboard.gps}`" target="_blank"
                                       class="relative z-10 mt-6 inline-flex items-center justify-center bg-orange-500 text-white py-2.5 px-4 text-sm rounded-md w-full hover:bg-orange-500 transition">
                                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5 14.5 7.62 14.5 9 13.38 11.5 12 11.5z"
                                            />
                                        </svg>
                                        View on Map
                                    </a>
                                </div>
                            </div>
                        </Link >

                    </div>
                </div>
            </div>

            <div v-else class="fixed inset-0 flex flex-col items-center justify-center text-center text-gray-600 px-4">
                <Briefcase class="w-20 h-20 mb-6 text-orange-500 opacity-80" />
                <h3 class="text-2xl font-semibold mb-2 text-gray-700">No Signboards yet</h3>
                <p class="text-base text-gray-500 mb-6">
                    You haven’t added any businesses yet. Click below to get started.
                </p>
                <SignboardCreate
                    :businesses="businesses"
                    :regions="regions"
                    @created="$inertia.reload({ only: ['signboards'] })">
                    <Button
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded shadow flex items-center gap-2"
                    >
                        <PlusIcon class="w-4 h-4" />
                        <span>Add First Signboard</span>
                    </Button>
                </SignboardCreate>
            </div>
        </div>

    </Layout>
</template>

