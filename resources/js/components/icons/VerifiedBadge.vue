<script setup lang="ts">
defineProps<{
    size?: number | string;
    color?: string;
    title?: string;
}>();
</script>

<template>
    <svg
        :width="size || 16"
        :height="size || 16"
        viewBox="0 0 24 24"
        :fill="color || '#1D9BF0'"
        xmlns="http://www.w3.org/2000/svg"
        :title="title || 'Verified'"
    >
        <path d="M12 0L15.09 3.48L20 4.91L18.36 9.49L20 14.09L15.09 15.51L12 19L8.91 15.51L4 14.09L5.64 9.49L4 4.91L8.91 3.48L12 0Z" />
        <path d="M10.293 13.293L7.707 10.707L9.121 9.293L10.293 10.465L14.879 5.879L16.293 7.293L10.293 13.293Z" fill="white"/>
    </svg>
</template>
