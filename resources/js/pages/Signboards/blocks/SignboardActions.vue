
<script setup lang="ts">
import { ExternalLink, Share2, Zap } from 'lucide-vue-next';
import { SignboardI } from '@/types';
import { toastSuccess } from '@/lib/helpers';

const props = defineProps<{ signboard: SignboardI }>();

const shareLocation = async () => {
    const signboardUrl = route('signboards.show', props.signboard.slug);
    const { gps_lat, gps_lon } = props.signboard;
    const mapsUrl = `https://maps.google.com/?q=${gps_lat},${gps_lon}`;

    const message = `Check out this signboard on SignboardGh:\n\nMap: ${mapsUrl}\nDetails: ${signboardUrl}`;

    if (navigator.share) {
        try {
            await navigator.share({
                title: 'Signboard Location',
                text: message,
                url: signboardUrl,
            });
        } catch (err) {
            console.error(err);
        }
    } else {
        await navigator.clipboard.writeText(message);
        toastSuccess('Link copied to clipboard!');

    }
};
</script>

<template>
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xl">
        <h3 class="mb-4 flex items-center gap-2 text-xl font-bold text-gray-900">
            <Zap class="h-5 w-5 text-primary" />
            Quick Actions
        </h3>
        <div class="space-y-3">
            <a
                :href="`https://maps.google.com/?q=${props.signboard.gps_lat},${props.signboard.gps_lon}`"
                target="_blank"
                class="group flex items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"
            >
                <div class="rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110">
                    <ExternalLink class="h-4 w-4 text-white" />
                </div>
                <span class="font-medium">View on Google Maps</span>
            </a>
            <button
                @click="shareLocation"
                class="group flex w-full items-center gap-3 rounded-xl bg-orange-50 p-3 text-primary transition-colors duration-200 hover:bg-blue-100"
            >
                <div class="rounded-lg bg-primary p-2 transition-transform duration-200 group-hover:scale-110">
                    <Share2 class="h-4 w-4 text-white" />
                </div>
                <span class="font-medium">Share Location</span>
            </button>
        </div>
    </div>
</template>
