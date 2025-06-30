<script setup lang="ts">
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet'
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';

const page = usePage()
const user = computed(() => page.props.auth.user)
</script>

<template>
    <Sheet>
        <SheetTrigger class="py-3 lg:py-0">
            <slot />
        </SheetTrigger>
        <SheetContent class="w-[80%]" side="left">
            <SheetDescription />
            <SheetTitle v-if="!user"></SheetTitle>
            <div class="w-full px-4">
                <div v-if="user" class="flex items-center justify-between">
                    <div class="text-fade" style="line-height: 16px">
                        <SheetTitle>{{ user.fullname }}</SheetTitle>
                        <div class="text-sm break-all">{{ user.email }}</div>
                    </div>
                    <Avatar class="h-12 w-12 border">
                        <AvatarImage :src="user.avatar?.original_url ?? ''" />
                        <AvatarFallback class="bg-gray-300">{{ user.initials }}</AvatarFallback>
                    </Avatar>
                </div>
                <div class="mt-3 grid grid-cols-1">
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline w-full ps-2"
                            :class="{ 'mobile-active-nav': $page.component === 'Home' }"
                            :href="route('home')"
                        >Home</TextLink>
                    </div>
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline w-full ps-2"
                            :class="{ 'mobile-active-nav': $page.component === 'Signboards/Signboards' }"
                            :href="route('signboards.index')"
                        >Browse Signboards</TextLink>
                    </div>
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline ps-2 w-full"
                            :class="{ 'mobile-active-nav': $page.component === 'Signboards/Signboard' }"
                            href="/templates"
                        >Locate A Signboard</TextLink>
                    </div>
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline ps-2 w-full"
                            :class="{ 'mobile-active-nav': $page.component === 'Signboards/Signboard' }"
                            href="/templates"
                        >Company</TextLink>
                    </div>
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline ps-2 w-full"
                            :class="{ 'mobile-active-nav': $page.component === 'Signboards/Help' }"
                            href="/help"
                        >Help</TextLink>
                    </div>
                </div>
                <div v-if="user" class="mt-3 grid grid-cols-1 border-t pt-3">
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline ps-2 w-full"
                            :class="{ 'mobile-active-nav': $page.component === 'Dashboard' }"
                            :href="route('dashboard')"
                        >Dashboard</TextLink>
                    </div>
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline ps-2 w-full"
                            :class="{ 'mobile-active-nav': $page.component === 'Businesses/MyBusinesses' }"
                            :href="route('my-businesses.index')"
                        >My Businesses</TextLink>
                    </div>
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline ps-2 w-full"
                            :class="{ 'mobile-active-nav': $page.component === 'Signboards/MySignboard' }"
                            href="/templates"
                        >My Signboards</TextLink>
                    </div>
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline ps-2 w-full"
                            :class="{ 'mobile-active-nav': $page.component === 'Profile/Show' }"
                            :href="route('profile.show')"
                        >Account Settings</TextLink>
                    </div>
                    <div class="flex items-center">
                        <Button as-child class="rounded-none w-full justify-start" variant="destructive">
                            <TextLink
                                :href="route('logout')"
                                method="post"
                                class="w-full no-underline font-bold"
                            >Logout</TextLink>
                        </Button>
                    </div>
                </div>
                <div v-else class="mt-3 grid grid-cols-1 border-t pt-3">
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline ps-2 w-full"
                            :class="{ 'mobile-active-nav': $page.component === 'auth/Login' }"
                            :href="route('login')"
                        >Login</TextLink>
                    </div>
                    <div class="flex items-center">
                        <TextLink
                            class="font-bold py-1.5 no-underline ps-2 w-full"
                            :class="{ 'mobile-active-nav': $page.component === 'auth/Register' }"
                            :href="route('register')"
                        >Join Now</TextLink>
                    </div>
                </div>
            </div>
        </SheetContent>
    </Sheet>
</template>

<style scoped>

</style>
