<script setup lang="ts">

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter, DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import InputText from '@/components/InputText.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { toastError, toastSuccess } from '@/lib/helpers';
import { computed, onMounted, ref } from 'vue';

const page = usePage()
const user = computed(()=> page.props.auth.user)
const isOpen = ref(false)

const form = useForm({
    facebook: "",
    x: "",
    linkedin: "",
    instagram: "",
})

onMounted(()=>{
    form.facebook = user.value.facebook
    form.x = user.value.x
    form.linkedin = user.value.linkedin
    form.instagram = user.value.instagram
})

const updateDetails = ()=>{
    form.patch(route('profile.edit-socials'), {
        onSuccess: (res) => {
            const message = res.props.message
            if (res.props.success) toastSuccess(message)
            else toastError(message)
            isOpen.value = false
        },
        preserveScroll: true
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
                <DialogTitle>Edit Socials</DialogTitle>
                <DialogDescription>
                    Make changes to your socials here. Click save when you're done.
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="updateDetails" id="update-form" class="grid gap-4 py-4 overflow-y-auto px-6">
                <InputText :form="form" label="Facebook" model="facebook" />
                <InputText :form="form" label="X (formally Twitter)" model="x" />
                <InputText :form="form" label="Linkedin" model="linkedin" />
                <InputText :form="form" label="Instagram" model="instagram" />
            </form>
            <DialogFooter class="p-3">
                <Button :processing="form.processing" type="submit" form="update-form">
                    Save Changes
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<style scoped>

</style>
