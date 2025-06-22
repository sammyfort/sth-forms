<script setup lang="ts">

import { Button } from '@/components/ui/button';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { LogOut } from 'lucide-vue-next';
import { LogInIcon, UserPlus2 } from 'lucide-vue-next';
import { Popover, PopoverTrigger, PopoverContent } from '@/components/ui/popover';
import { HandHelping, UserRoundCog, LayoutDashboard } from 'lucide-vue-next';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';


const page = usePage()
const user = computed(() => page.props.auth.user)

const logout = ()=>{
    router.post(route('logout'))
}
</script>

<template>
    <nav class="sticky top-0 z-50 w-full flex justify-center">
        <div class="container">
            <div class="flex items-center justify-between gap-2 bg-background/50 py-4 backdrop-blur-md lg:mt-4 lg:rounded-2xl lg:border lg:px-4">
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-12">
                        <Link class="flex items-center gap-2.5" :href="route('home')">
                            <img src="/images/logo.png" class="size-12 rounded-md" alt="Signboard Logo" />
                            <h2 class="text-md font-bold">Signboard Ghana</h2>
                        </Link>
                        <div class="hidden items-center gap-5 text-sm font-medium text-muted-foreground lg:flex">
                            <a target="" class="hover:text-foreground" href="/blocks">Home</a>
                            <a target="" class="hover:text-foreground" href="/templates">Signboards</a>
                            <a target="" class="hover:text-foreground" href="/templates">Businesses</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-4">
                        <div v-if="!user" class="hidden gap-1 sm:flex">
                            <Button  @click="router.visit(route('login'))" variant="secondary" class="px-[1rem]">
                                <div><LogInIcon /></div>
                                Login
                            </Button>
                        </div>
                        <div v-if="!user" class="ms-4 flex flex-col items-center space-x-1">
                            <Button  @click="router.visit(route('register'))" class="px-[1rem]">
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
                                        <div class="text-fade" style="line-height: 16px">
                                            <div class="font-bold">{{ user?.fullname }}</div>
                                            <div class="text-sm">{{ user.email }}</div>
                                        </div>
                                    </div>
                                </PopoverTrigger>
                                <PopoverContent class="w-60">
                                    <div class="grid">
                                        <Link href="#" class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2">
                                            <span>Dashboard Area</span>
                                            <LayoutDashboard class="ms-auto" :size="16"/>
                                        </Link>
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
                        <button class="md:hidden">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-panel-right size-6"
                                aria-hidden="true"
                            >
                                <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                                <path d="M15 3v18"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>

</style>
