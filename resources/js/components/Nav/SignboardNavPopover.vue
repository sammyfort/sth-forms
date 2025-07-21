<script setup lang="ts">

import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <Popover>
        <PopoverTrigger>
            <slot />
        </PopoverTrigger>
        <PopoverContent>
            <div class="grid">
                <Link
                    :href="route('my-businesses.index')"
                    class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2"
                    :class="{ 'text-primary': $page.component === 'Businesses/MyBusinesses' }"
                >
                    Overview
                </Link>
                <Link
                    :href="route('my-businesses.index')"
                    class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2"
                    :class="{ 'text-primary': $page.component === 'Businesses/MyBusinesses' }"
                >
                    My Businesses
                </Link>
                <Link
                    :href="route('my-signboards.index')"
                    class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2"
                    :class="{ 'text-primary': $page.component === 'Signboards/MySignboards' }"
                >My Signboards</Link>

            </div>
        </PopoverContent>
    </Popover>
</template>

<style scoped>

</style>
