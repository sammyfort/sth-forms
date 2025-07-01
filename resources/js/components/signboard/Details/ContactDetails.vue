<script setup lang="ts">

import { AtSign, PhoneOutgoing } from 'lucide-vue-next';
import { SignboardI } from '@/types';

type Props = {
    signboard: SignboardI
}
const props = defineProps<Props>()
const business = props.signboard.business

</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="text-fade">
            Contact Details
        </div>
        <div class="flex flex-wrap gap-2">
            <div class="flex gap-1 items-center">
                <AtSign :size="17" class="text-secondary"/>
                <span class="font-medium">Email:</span>
            </div>
            <span class="break-all">{{ business.email }}</span>
        </div>
        <div class="flex flex-wrap gap-2">
            <div class="flex gap-1 items-center">
                <PhoneOutgoing :size="17" class="text-secondary"/>
                <span class="font-medium">Mobile:</span>
            </div>
            <span class="break-all">{{ business.mobile }}</span>
        </div>
    </div>
</template>

<style scoped>

</style>
