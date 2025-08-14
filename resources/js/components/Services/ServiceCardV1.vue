<script setup lang="ts">
import { HTMLAttributes, ref } from 'vue';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import { MapPin, Eye } from 'lucide-vue-next';
import { ServiceI } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import StarRating from 'vue-star-rating'


const props = defineProps<{
    class?: HTMLAttributes['class'],
    service: ServiceI
}>()

const totalAverageRating = ref<number>(props.service.total_average_rating)

</script>

<template>
    <Card
        :class="cn('shadow-sm border-0 py-2 rounded-none gap-1 transform transition-transform duration-300 hover:scale-99 cursor-pointer', {'border-1 border-primary': service.is_advertised}, props.class)"
        @click="router.visit(route('services.show', service.slug))"
    >
        <CardHeader>
            <div class="overflow-hidden">
                <div class="font-semibold p-0 m-0 leading-tight truncate">
                    <TooltipProvider>
                        <Tooltip>
                            <TooltipTrigger as-child>
                                <Link
                                    :href="route('services.show', service.slug)"
                                    class="hover:underline hover:text-secondary"
                                >{{ service.title }}</Link>
                            </TooltipTrigger>
                            <TooltipContent>
                                {{ service.title }}
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>
                </div>
                <div class="text-fade leading-tight">
                    <div class="truncate text-sm">{{ service.user.fullname }}</div>
                    <div class="text-xs truncate">{{ service.business_name }}</div>
                </div>
            </div>
        </CardHeader>
        <CardContent class="">
            <TooltipProvider>
                <div class="relative">
                    <img
                        class="object-end w-full object-cover max-h-40 object-center"
                        :src="service.featured as string"
                        alt="Featured Image"
                    >
                    <div class="absolute bottom-[0px] right-0 px-2 bg-primary/50 text-sm text-white truncate max-w-[150px]">
                    <Tooltip>
                        <TooltipTrigger>
                                {{ service.category.name }}
                        </TooltipTrigger>
                        <TooltipContent>
                            {{ service.category.name }}
                        </TooltipContent>
                    </Tooltip>
                    </div>
                    <div class="absolute top-[0px] right-0 p-1">
                        <Avatar>
                            <AvatarImage class="border-1 rounded-full" :src="service.user.avatar?.original_url ?? ''"/>
                            <AvatarFallback class="shadow-lg">{{ service.user.initials }}</AvatarFallback>
                        </Avatar>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex gap-2 text-secondary items-center">
                        <div v-if="service.region" class="flex gap-1 items-center truncate">
                            <MapPin :size="15" class="text-primary"/>
                            <div class="truncate">
                                <div v-if="service.region.country" class="text-xs text-fade truncate">{{ service.region.country.name }}</div>
                                <div v-if="service.region" class="text-sm truncate">{{ service.region.name }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col ms-auto gap-0 text-xs items-end">
                            <div class="flex gap-1"><Eye :size="13"/> {{ service.views_count }}</div>
                            <div>
                                <StarRating
                                    :star-size="12"
                                    :show-rating="false"
                                    :rating="totalAverageRating"
                                    read-only
                                    active-color="#009689"
                                    :padding="0"
                                    :key="`rating-card-${service.id}`"
                                    :increment="0.01"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </TooltipProvider>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
