<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { ServiceI } from '@/types';

type Props = {
    service: ServiceI
}

const props = defineProps<Props>()
</script>

<template>
    <Layout>
        Service {{ service.title }}
    </Layout>
</template>

<style scoped>

</style>
