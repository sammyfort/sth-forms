<script setup lang="ts">

import { Facebook, Instagram, Linkedin } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import ContactUsSheet from '@/components/ContactUsSheet.vue';
</script>

<template>
    <footer class="bg-gradient-to-r from-gray-900 via-gray-700 to-gray-900  pt-12 pb-6 px-10 font-sans tracking-wide relative">
        <div class="max-w-screen-xl mx-auto">


            <hr class="mt-12 mb-6 border-gray-600" />

            <div class="flex sm:justify-between flex-wrap gap-6">
                <div class="flex space-x-5">
                    <a href="javascript:void(0)" class="text-gray-400 hover:text-primary text-sm transition-all">
                        <Facebook />
                    </a>
                    <a href="javascript:void(0)" class="text-gray-400 hover:text-primary text-sm transition-all">
                        <Instagram />
                    </a>
                    <a href="javascript:void(0)" class="text-gray-400 hover:text-primary text-sm transition-all">
                        <Linkedin />
                    </a>
                </div>

                <p class="text-gray-400 text-sm">© Signboard Ghana. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>

<style scoped>

</style>
