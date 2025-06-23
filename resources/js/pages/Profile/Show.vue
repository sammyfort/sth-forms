<script setup lang="ts">

import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Facebook, Instagram, Linkedin, UserPen } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import InputText from '@/components/InputText.vue';
import PersonalDetailsCard from '@/components/profile/PersonalDetailsCard.vue';
import SocialsCard from '@/components/profile/SocialsCard.vue';
</script>

<template>
    <Layout>
        <Head title="Profile"/>
        <div class="flex w-full py-6 lg:justify-center">
            <div class="w-full">
                <div class="mb-3 font-black text-xl">
                    Profile Details
                </div>
                <div class="flex flex-wrap">
                    <Card class="border shadow-none border-gray-200 rounded-2xl lg:w-3/4 w-full">
                        <CardHeader class="text-lg font-black">Profile</CardHeader>
                        <CardContent>
                            <SocialsCard />
                            <PersonalDetailsCard />
                            <Card class="mt-8 border shadow-none border-gray-200 rounded-2xl">
                                <CardHeader class="text-lg font-black">Password</CardHeader>
                                <CardContent>
                                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                                        <div class="md:w-1/3">
                                            <InputText placeholder="****************************" disabled/>
                                        </div>
                                        <Button variant="destructive" class="flex items-center justify-center gap-2 rounded-full border px-8 h-11 py-3">
                                            <div>
                                                <UserPen />
                                            </div>
                                            <div>Change Password</div>
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        </CardContent>
                    </Card>
                    <div class="w-full lg:w-1/4 mt-8 lg:mt-0 lg:ps-8">
                        <Card class="border shadow-none border-gray-200 rounded-2xl">
                            <CardHeader class="text-lg font-black">Recent Activities</CardHeader>
                            <CardContent>
                                <div>
                                    sakjflks
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
