<script setup lang="ts">

import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Link } from '@inertiajs/vue3';
import ContactUsSheet from '@/components/ContactUsSheet.vue';
</script>

<template>
    <Popover>
        <PopoverTrigger>
            <slot />
        </PopoverTrigger>
        <PopoverContent>
            <div class="grid">
                <Link
                    :href="route('about-us')"
                    class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2"
                    :class="{ 'text-primary': $page.component === 'AboutUs' }"
                >
                    About Us
                </Link>
                <Link
                    :href="route('faq.index')"
                    class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2"
                    :class="{ 'text-primary': $page.component === 'FAQ' }"
                >FAQs</Link>
                <ContactUsSheet>
                    <div class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2 cursor-pointer">
                        Contact Us
                    </div>
                </ContactUsSheet>
                <ContactUsSheet>
                    <div class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2 cursor-pointer">
                        Career
                    </div>
                </ContactUsSheet>
            </div>
        </PopoverContent>
    </Popover>
</template>

<style scoped>

</style>
