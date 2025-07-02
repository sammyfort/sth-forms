<script setup lang="ts">

import { Facebook, Instagram, Linkedin } from 'lucide-vue-next';
import { SignboardI } from '@/types';


type Props = {
    signboard: SignboardI
}
const props = defineProps<Props>()
const business = props.signboard.business

</script>

<template>
    <div class="flex flex-col gap-3">
        <div class="text-fade">
            Socials
        </div>
        <div class="flex items-center gap-3 text-secondary">
            <a v-if="business.facebook" target="_blank" :href="business.facebook">
                <Facebook />
            </a>
            <a v-if="business.linkedin" target="_blank" :href="business.linkedin">
                <Linkedin />
            </a>
            <a v-if="business.instagram" target="_blank" :href="business.instagram">
                <Instagram />
            </a>
            <a v-if="business.x" target="_blank" :href="business.x">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M15.1708 1.875H17.9274L11.9049 8.75833L18.9899 18.125H13.4424L9.09742 12.4442L4.12578 18.125H1.36745L7.80912 10.7625L1.01245 1.875H6.70078L10.6283 7.0675L15.1708 1.875ZM14.2033 16.475H15.7308L5.87078 3.43833H4.23162L14.2033 16.475Z"
                        fill=""
                    ></path>
                </svg>
            </a>
        </div>
    </div>
</template>

<style scoped>

</style>
