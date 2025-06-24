<script setup lang="ts">

import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import InputText from '@/components/InputText.vue';
import { LockIcon } from 'lucide-vue-next';
import ChangePasswordDialog from '@/components/profile/ChangePasswordDialog.vue';
</script>

<template>
    <Card class="mt-8 border shadow-none border-gray-200 rounded-2xl">
        <CardHeader class="text-lg font-black">Password</CardHeader>
        <CardContent>
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="md:w-1/3">
                    <InputText placeholder="****************************" disabled/>
                </div>
                <ChangePasswordDialog>
                    <Button variant="destructive" class="flex items-center justify-center gap-2 rounded-full border px-8 h-11 py-3">
                        <div><LockIcon /></div>
                        <div>Change Password</div>
                    </Button>
                </ChangePasswordDialog>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
