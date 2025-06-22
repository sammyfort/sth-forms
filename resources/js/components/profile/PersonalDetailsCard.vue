<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { UserPen } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import EditPersonalDetailsDialog from '@/components/profile/EditPersonalDetailsDialog.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage()
const user = computed(()=> page.props.auth.user)

</script>

<template>
    <Card class="mt-8 border shadow-none border-gray-200 rounded-2xl">
        <CardHeader class="text-lg font-black">Personal Details</CardHeader>
        <CardContent>
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 ">
                                First Name
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ user.firstname }}
                            </p>
                        </div>
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Last Name
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ user.lastname }}
                            </p>
                        </div>
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Email
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ user.email }}
                            </p>
                        </div>
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Mobile Number
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ user.mobile }}
                            </p>
                        </div>
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500">
                                Last Login
                            </p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ user.last_login }}
                            </p>
                        </div>
                    </div>
                </div>
                <EditPersonalDetailsDialog>
                    <Button class="flex items-center justify-center gap-2 rounded-full border px-8 h-11 py-3" variant="secondary">
                        <div>
                            <UserPen />
                        </div>
                        <div>Edit</div>
                    </Button>
                </EditPersonalDetailsDialog>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
