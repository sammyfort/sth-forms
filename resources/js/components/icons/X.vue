<script setup lang="ts">
import { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    class?: HTMLAttributes['class']
}>()
</script>

<template>
    <svg :class="cn('transition-transform transform hover:scale-110', props.class)" role="img" viewBox="0 0 46 46" xmlns="http://www.w3.org/2000/svg" id="X--Streamline-Simple-Icons" height="46" width="46">
        <path d="M36.22691666666667 2.209916666666667h7.053333333333334l-15.409999999999998 17.614166666666666L46 43.78816666666667h-14.194833333333333l-11.116666666666667 -14.536 -12.722833333333334 14.536H0.9085l16.483333333333334 -18.840833333333332L0 2.2118333333333333h14.555166666666668l10.049083333333334 13.286333333333335ZM33.7525 39.56766666666667h3.908083333333334L12.4315 6.210000000000001H8.237833333333334Z" fill="#000000" stroke-width="1.9167"></path>
    </svg>
</template>

<style scoped>

</style>
