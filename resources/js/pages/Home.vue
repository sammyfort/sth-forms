<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import HeroSection from '@/components/home/HeroSection.vue';
import { useForm } from '@inertiajs/vue3';




</script>
<template>
    <Layout class="p-0 lg:p-0 md:p-0" style="padding: 0 !important;" :is-full-width="true">
        <div class="w-full">
            <HeroSection />



        </div>
    </Layout>
</template>
