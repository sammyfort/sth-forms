<script setup lang="ts">

import { Button } from '@/components/ui/button';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { LogOut } from 'lucide-vue-next';
import { UserPlus2, Milestone, Building2, Menu, ChevronDown } from 'lucide-vue-next';
import { Popover, PopoverTrigger, PopoverContent } from '@/components/ui/popover';
import { HandHelping, UserRoundCog, LayoutDashboard } from 'lucide-vue-next';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import MobileNav from '@/layouts/MobileNav.vue';
import CompanyNavPopover from '@/components/Nav/CompanyNavPopover.vue';
import SignboardNavPopover from '@/components/Nav/SignboardNavPopover.vue';
import ServiceNavPopover from '@/components/Nav/ServiceNavPopover.vue';


const page = usePage()
const user = computed(() => page.props.auth.user)


</script>

<template>
    <nav class="sticky top-4 md:top-0 z-50 w-full flex justify-center">
        <div class="w-full">
            <div class="flex items-center justify-between gap-2 bg-background/50 lg:py-4 backdrop-blur-md lg:mt-4 lg:rounded-2xl lg:border lg:px-4 px-3">
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-12">
                        <Link class="hidden lg:flex items-center gap-2.5" :href="route('home')">
<!--                            <img src="/images/logo.png" class="size-15 rounded-md" alt="SHT Logo" />-->
                            <h2 class="text-2xl font-bold text-center">STH R&D</h2>
                        </Link>
                        <MobileNav>
                            <Menu class="lg:hidden" :size="35"/>
                        </MobileNav>
                        <div class="hidden items-center gap-5 text-sm font-medium text-muted-foreground lg:flex">
                            <Link
                                :class="{ 'active-nav': $page.component === 'Home' }"
                                :href="route('home')"
                                class="hover:text-primary py-4 text-center"
                            >Home</Link>

                        </div>
                    </div>
                </div>
                <Link class="flex lg:hidden items-center gap-3 mx-auto py-2" :href="route('home')">
<!--                    <img src="/images/logo.png" class="size-14 rounded-md" alt="STH Logo" />-->
                    <h2 class="text-lg font-bold">STH RC</h2>
                </Link>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-4">
                        <div v-if="user" class="hidden items-center gap-5 text-sm font-medium text-muted-foreground lg:flex">
                            <Link
                                :class="{ 'active-nav': $page.component === 'Dashboard/Dashboard' }"
                                :href="route('dashboard')"
                                class="hover:text-primary flex items-center gap-1 py-4 text-center"
                            >
                                <LayoutDashboard  :size="15" class="text-secondary"/> Dashboard
                            </Link>


                        </div>

                        <div v-if="!user" class="hidden lg:block gap-1">
                            <Link :href="route('buy-voucher.index')" variant="secondary" class="px-[1rem] gap-2 items-center flex font-black text-secondary hover:text-primary">
                                Buy Voucher
                            </Link>
                        </div>
                        <div v-if="!user" class="hidden lg:flex flex-col items-center space-x-1">
                            <Button  @click="router.visit(route('research.apply'))" class="px-[1rem] cursor-pointer hover:bg-secondary">
                                <div><UserPlus2 /></div>
                                Apply
                            </Button>
                        </div>
                        <div v-else class="ms-4 flex gap-4 items-center space-x-1">
                            <Popover>
                                <PopoverTrigger as-child>
                                    <div class="lex flex items-center cursor-pointer gap-3">
                                        <Avatar class="h-12 w-12 border">
                                            <AvatarImage :src="user.avatar?.original_url ?? ''" />
                                            <AvatarFallback class="bg-gray-300 uppercase">{{ user.initials }}</AvatarFallback>
                                        </Avatar>
                                    </div>
                                </PopoverTrigger>
                                <PopoverContent class="w-60" align="end">
                                    <div class="text-fade mb-3" style="line-height: 16px">
                                        <div class="font-bold flex gap-1 flex-wrap">
                                            {{ user?.fullname }}
                                        </div>
                                        <div class="text-sm break-all">{{ user.email }}</div>
                                    </div>
                                    <div class="grid">

                                        <Link href="#" class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2">
                                            <span>Help</span>
                                            <HandHelping class="ms-auto" :size="16"/>
                                        </Link>
                                        <Link
                                            :href="route('logout')"
                                            method="post"
                                            class="p-1.5 border-0 text-destructive cursor-pointer hover:bg-destructive items-center hover:text-white flex gap-2"
                                        >
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
