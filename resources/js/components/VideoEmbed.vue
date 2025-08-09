<script setup lang="ts">
import { computed, HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    url: string;
    class?: HTMLAttributes['class'];
}>();

const embedCode = computed(() => {
    const url = props.url.trim();

    if (/\.(mp4|webm|ogg|m3u8|mov)$/i.test(url)) {
        return `<video
                    style="width: 100%"
                    height="360"
                    controls
                >
                    <source src="${url}">
                    Your browser does not support the video tag.
                </video>`;
    }
    return `<iframe
                src="${url.replace('watch?v=', 'embed/')}"
                style="width: 100%"
                height="360"
                frameborder="0"
                allow="autoplay; encrypted-media; picture-in-picture"
                allowfullscreen>
            </iframe>`;
});

</script>

<template>
    <div v-html="embedCode" :class="cn('', props.class)"></div>
</template>
