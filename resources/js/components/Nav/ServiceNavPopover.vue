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
                    :href="route('my-services.index')"
                    class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2"
                    :class="{ 'text-primary': $page.component === 'Services/MyServices' }"
                >
                    My Services
                </Link>

                <Link
                    :href="route('my-jobs.index')"
                    class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2"
                    :class="{ 'text-primary': $page.component === 'Jobs/MyJobs' }"
                >
                    My Jobs
                </Link>

                <Link
                    :href="route('my-products.index')"
                    class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2"
                    :class="{ 'text-primary': $page.component === 'Products/MyProducts' }"
                >
                    My Products
                </Link>
            </div>
        </PopoverContent>
    </Popover>
</template>

<style scoped>

</style>
