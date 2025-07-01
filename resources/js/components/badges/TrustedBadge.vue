<script setup lang="ts">

import { HTMLAttributes } from 'vue';
import { Handshake } from 'lucide-vue-next';
import { cn } from '@/lib/utils';


type Props = {
    class?: HTMLAttributes['class'],
    size?: number|undefined
}
const props = withDefaults(defineProps<Props>(), {
    size: 15
})

</script>

<template>
    <Handshake :size="props.size" :class="cn('', props.class)"/>
</template>

<style scoped>

</style>
