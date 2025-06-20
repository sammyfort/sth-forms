<script setup lang="ts">

import { Search } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'
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
                            <a target="" class="hover:text-foreground" href="/blocks">Blocks</a>
                            <a target="" class="hover:text-foreground" href="/templates">Templates</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-4">
                        <div class="hidden gap-1 sm:flex">
                            <div>
                                <div class="relative">
                                    <button
                                        data-slot="button"
                                        class="inline-flex size-9 items-center justify-center gap-2 transition-all outline-none"
                                    >
                                        <Search />
                                    </button>
                                </div>
                            </div>
                            <Link :href="route('login')" class="inline-flex items-center justify-center">Login</Link>
                        </div>
                        <div class="ms-4 flex items-center space-x-1">
                            <Button @click="router.visit(route('register'))">
                                Join
                            </Button>
                        </div>
                        <button>
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
