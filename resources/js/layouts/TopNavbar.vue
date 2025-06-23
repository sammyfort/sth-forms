<script setup lang="ts">

import { Button } from '@/components/ui/button';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { LogOut } from 'lucide-vue-next';
import { LogInIcon, UserPlus2, Milestone, Building2, Menu } from 'lucide-vue-next';
import { Popover, PopoverTrigger, PopoverContent } from '@/components/ui/popover';
import { HandHelping, UserRoundCog, LayoutDashboard } from 'lucide-vue-next';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';


const page = usePage()
const user = computed(() => page.props.auth.user)
</script>

<template>
    <nav class="sticky top-0 z-50 w-full flex justify-center">
        <div class="w-full">
            <div class="flex items-center justify-between gap-2 bg-background/50 lg:py-4 backdrop-blur-md lg:mt-4 lg:rounded-2xl lg:border lg:px-4">
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-12">
                        <Link class="hidden lg:flex items-center gap-2.5" :href="route('home')">
                            <img src="/images/logo.png" class="size-12 rounded-md" alt="Signboard Logo" />
                            <h2 class="text-md font-bold">Signboard Ghana</h2>
                        </Link>
                        <Menu class="lg:hidden" :size="35"/>
                        <div class="hidden items-center gap-5 text-sm font-medium text-muted-foreground lg:flex">
                            <Link
                                :class="{ 'active-nav': $page.component === 'Home' }"
                                class="hover:text-primary py-4"
                                :href="route('home')"
                            >Home</Link>
                            <Link
                                :class="{ 'active-nav': $page.component === 'Businesses/Businesses' }"
                                class="hover:text-primary py-4"
                                :href="route('businesses.index')"
                            >Browse Businesses</Link>
                            <Link
                                :class="{ 'active-nav': $page.component === 'Signboards/Signboards' }"
                                class="hover:text-primary"
                                href="/templates"
                            >Find A Business</Link>
                            <Link
                                :class="{ 'active-nav': $page.component === 'Signboards/Signboards' }"
                                class="hover:text-primary"
                                href="/templates"
                            >Company</Link>
                        </div>
                    </div>
                </div>
                <Link class="flex lg:hidden items-center gap-1 mx-auto" :href="route('home')">
                    <img src="/images/logo.png" class="size-7 rounded-md" alt="Signboard Logo" />
                    <h2 class="text-md font-bold">Signboard GH</h2>
                </Link>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-4">
                        <div v-if="user" class="hidden items-center gap-5 text-sm font-medium text-muted-foreground lg:flex">
                            <Link
                                :class="{ 'active-nav': $page.component === 'Dashboard' }"
                                class="hover:text-primary flex items-center gap-1 py-4"
                                :href="route('dashboard')"
                            >
                                <LayoutDashboard  :size="15" class="text-secondary"/> Dashboard
                            </Link>
                            <Link
                                :class="{ 'active-nav': $page.component === 'Businesses/MyBusinesses' }"
                                class="hover:text-primary flex items-center gap-1 py-4"
                                :href="route('businesses.mine')"
                            >
                                <Building2 :size="15" class="text-secondary"/> My Business
                            </Link>
                            <Link
                                :class="{ 'active-nav': $page.component === 'Signboards/MySignboard' }"
                                class="hover:text-primary flex items-center gap-1 py-4"
                                href="/templates"
                            >
                                <Milestone :size="15" class="text-secondary"/> My Signboards
                            </Link>
                        </div>
                        <div v-if="!user" class="hidden gap-1 sm:flex">
                            <Link :href="route('login')" variant="secondary" class="px-[1rem] gap-2 items-center flex font-black text-secondary hover:text-primary">
                                Login
                            </Link>
                        </div>
                        <div v-if="!user" class="flex flex-col items-center space-x-1">
                            <Button  @click="router.visit(route('register'))" class="px-[1rem] cursor-pointer hover:bg-secondary">
                                <div><UserPlus2 /></div>
                                Join Now
                            </Button>
                        </div>
                        <div v-else class="ms-4 flex gap-4 items-center space-x-1">
                            <Popover>
                                <PopoverTrigger as-child>
                                    <div class="lex flex items-center cursor-pointer gap-3">
                                        <Avatar class="h-12 w-12 border">
                                            <AvatarImage :src="user.avatar?.original_url ?? ''" />
                                            <AvatarFallback class="bg-gray-300">{{ user.initials }}</AvatarFallback>
                                        </Avatar>
                                        <div class="text-fade hidden md:block" style="line-height: 16px">
                                            <div class="font-bold">{{ user?.fullname }}</div>
                                            <div class="text-sm">{{ user.email }}</div>
                                        </div>
                                    </div>
                                </PopoverTrigger>
                                <PopoverContent class="w-60" align="end">
                                    <div class="grid">
                                        <Link :href="route('profile.show')" class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2">
                                            <span>Account Settings</span>
                                            <UserRoundCog class="ms-auto" :size="16"/>
                                        </Link>
                                        <Link href="#" class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2">
                                            <span>Help</span>
                                            <HandHelping class="ms-auto" :size="16"/>
                                        </Link>
                                        <Link :href="route('logout')" method="post" class="p-1.5 border-0 text-destructive cursor-pointer hover:bg-destructive items-center hover:text-white flex gap-2">
                                            <span>Logout</span>
                                            <LogOut class="ms-auto" :size="16"/>
                                        </Link>
                                    </div>
                                </PopoverContent>
                            </Popover>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>

</style>
