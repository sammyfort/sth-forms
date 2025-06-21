<script setup lang="ts">

import { Button } from '@/components/ui/button';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { User2 } from 'lucide-vue-next';
import { LogInIcon, UserPlus2 } from 'lucide-vue-next';

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
                        <a class="flex items-center gap-2.5" href="/"
                        ><img src="/images/logo.png" class="size-12 rounded-md" alt="Signboard Logo" />
                            <h2 class="text-md font-bold">Signboard Ghana</h2></a
                        >
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
                            <Button  @click="router.visit(route('login'))" variant="secondary" class="px-[3.5rem]">
                                <div><LogInIcon /></div>
                                Login
                            </Button>
                        </div>
                        <div v-if="!user" class="ms-4 flex flex-col items-center space-x-1">
                            <Button  @click="router.visit(route('register'))" class="px-[3.5rem]">
                                <div><UserPlus2 /></div>
                                Join Now
                            </Button>
                        </div>
                        <div v-else class="ms-4 flex gap-4 items-center space-x-1">
                            <div class="lex flex flex-col items-center justify-center cursor-pointer">
                                <User2 />
                                <div>{{ user?.lastname }}</div>
                            </div>
                            <Button class="bg-destructive hover:bg-red-700" @click="logout">Logout</Button>
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
