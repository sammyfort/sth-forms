<script setup lang="ts">

import {
    Dialog,
    DialogContent,
    DialogDescription, DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import InputText from '@/components/InputText.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { alertResponse } from '@/lib/helpers';

const isOpen = ref<boolean>(false)

const form = useForm({
    'current_password': "",
    'password': "",
    'password_confirmation': '',
})
const changePassword = ()=>{
    form.put(route('profile.password'), {
        onSuccess: (res) => {
            alertResponse(res, ()=> isOpen.value = false)
        }
    })
}

</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px] grid-rows-[auto_minmax(0,1fr)_auto] p-0 max-h-[90dvh]">
            <DialogHeader class="p-6 pb-0">
                <DialogTitle>Change Your Password</DialogTitle>
                <DialogDescription>
                    Set your new password here. Click save when you're done.
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="changePassword" id="update-form" class="grid gap-4 py-4 overflow-y-auto px-6">
                <InputText :form="form" type="password" label="Your Current Password" model="current_password" />
                <InputText :form="form" type="password" label="New Password" model="password" />
                <InputText :form="form" type="password" label="Password Confirmation" model="password_confirmation" />
            </form>
            <DialogFooter class="p-3">
                <Button :processing="form.processing" type="submit" form="update-form">
                    Change Password
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<style scoped>

</style>
