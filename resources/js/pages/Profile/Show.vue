<script setup lang="ts">

import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import PersonalDetailsCard from '@/components/profile/PersonalDetailsCard.vue';
import SocialsCard from '@/components/profile/SocialsCard.vue';
import ChangePasswordCard from '@/components/profile/ChangePasswordCard.vue';
</script>

<template>
    <Layout>
        <Head title="Profile"/>
        <div class="flex w-full py-6 lg:justify-center">
            <div class="w-full">
<!--                <div class="mb-5 font-black text-2xl text-center">-->
<!--                    Profile Details-->
<!--                </div>-->
                <div class="flex flex-wrap justify-center">
                    <Card class="border shadow-none border-gray-200 rounded-2xl lg:w-3/4 w-full">
                        <CardHeader class="text-lg font-black">
                            <div class="font-black text-2xl">
                                Profile Details
                            </div>
                        </CardHeader>
                        <CardContent>
                            <SocialsCard />
                            <PersonalDetailsCard />
                            <ChangePasswordCard />
                        </CardContent>
                    </Card>
<!--                    <div class="w-full lg:w-1/4 mt-8 lg:mt-0 lg:ps-8">-->
<!--                        <Card class="border shadow-none border-gray-200 rounded-2xl">-->
<!--                            <CardHeader class="text-lg font-black">Recent Activities</CardHeader>-->
<!--                            <CardContent>-->
<!--                                <div>-->
<!--                                    sakjflks-->
<!--                                </div>-->
<!--                            </CardContent>-->
<!--                        </Card>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
