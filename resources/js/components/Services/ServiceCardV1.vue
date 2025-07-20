<script setup lang="ts">
import { HTMLAttributes } from 'vue';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import { MapPin } from 'lucide-vue-next';
import { ServiceI } from '@/types';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps<{
    class?: HTMLAttributes['class'],
    service: ServiceI
}>()
</script>

<template>
    <Card
        :class="cn('shadow-sm border-0 py-2 rounded-none gap-1 transform transition-transform duration-300 hover:scale-99 cursor-pointer', {'border-1 border-primary': service.is_advertised}, props.class)"
        @click="router.visit(route('services.show', service.slug))"
    >
        <CardHeader>
            <div class="overflow-hidden">
                <div class="font-semibold p-0 m-0 leading-tight truncate">
                    <Link
                        :href="route('services.show', service.slug)"
                        class="hover:underline hover:text-secondary"
                    >{{ service.title }}</Link>
                </div>
                <div class="text-fade leading-tight">
                    <div class="truncate text-sm">{{ service.user.fullname }}</div>
                    <div class="text-xs truncate">{{ service.business_name }}</div>
                </div>
            </div>
        </CardHeader>
        <CardContent class="">
            <div class="relative">
                <img
                    class="object-end w-full object-cover max-h-30 object-center"
                    :src="service.featured"
                    alt="Featured Image"
                >
                <div class="absolute bottom-[0px] right-0 px-2 bg-primary/50 text-sm text-white">
                    Carpenter
                </div>
            </div>
            <div class="mt-3">
                <div class="flex gap-2 text-secondary items-center">
                    <MapPin :size="15" class="text-primary"/>
                    <div>
                        <div class="text-sm">{{ service.region.name }}</div>
                        <div class="text-xs text-fade truncate">{{ service.town }}</div>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
