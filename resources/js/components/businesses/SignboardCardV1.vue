<script setup lang="ts">
import { cn } from '@/lib/utils';
import { HTMLAttributes } from 'vue';
import { SignboardI } from '@/types';
import { MapPin, MapPinned, Pin, Dot, Handshake } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger} from '@/components/ui/tooltip'

const props = withDefaults(
    defineProps<{
        class?: HTMLAttributes['class'],
        imageHeight? : string,
        signboard: SignboardI
    }>(),
    {
        imageHeight: "48"
    }
)

</script>

<template>
    <div :class="cn('overflow-hidden relative rounded-lg bg-white shadow hover:border hover:border-primary cursor-pointer transform transition-transform duration-300 hover:scale-98', props.class)">
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <div class="absolute top-3 p-1 rounded-[50%] bg-secondary right-3 text-white">
                        <Handshake :size="15"/>
                    </div>
                </TooltipTrigger>
                <TooltipContent side="bottom">
                    Trusted Business
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
        <img
            class="object-end w-full object-cover" :class="'h-'+imageHeight"
            src="https://images.unsplash.com/photo-1570797197190-8e003a00c846?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=968&q=80"
            alt="Home in Countryside"
        />
        <div class="p-4 h-[270px] flex flex-col gap-2">
            <div class="flex flex-wrap gap-x-2 gap-y-0 text-xs">
                <div class="flex items-center truncate" v-for="category in signboard.categories" :key="category.id">
                    <Dot class="text-primary h-5 w-5" :size="35"/>
                    <div>{{ category.name }}</div>
                </div>
            </div>

            <div>
                <div class="flex items-center gap-1">
                    <h4 class="truncate text-lg leading-tight font-semibold">
                        <Link href="" class="hover:underline hover:text-secondary">{{ signboard.business.name }}</Link>
                    </h4>
                </div>
                <div class="mt-1 flex gap-2 items-center text-sm">
                    <MapPin :size="15" class="text-primary"/>
                    <div>{{ signboard.region.name }} Region</div>
                </div>
                <div class="mt-1 flex gap-2 items-center text-sm">
                    <MapPinned :size="15" class="text-primary"/>
                    <div>{{ signboard.town }}</div>
                </div>
                <div class="mt-1 flex gap-2 items-center text-sm">
                    <Pin :size="15" class="text-primary"/>
                    <div>{{ signboard.street }}</div>
                </div>
            </div>

            <div class="flex items-center">
                <span class="font-semibold text-teal-600">
                    <span>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </span>
                </span>
                <span class="ml-2 text-sm text-gray-600">34 reviews</span>
            </div>

            <div class="mt-auto ms-auto w-full flex items-baseline">
                <Link href="" class="underline text-primary hover:text-secondary" size="sm">View Business</Link>
                <Button size="sm" variant="secondary" class="ms-auto">Contact</Button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
