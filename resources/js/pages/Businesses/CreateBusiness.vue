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
import { useForm} from '@inertiajs/vue3';
import { toastError, toastSuccess } from '@/lib/helpers';
import {ref } from 'vue';
import { LoaderCircle } from 'lucide-vue-next';

const isOpen = ref(false)
const form = useForm({
    name: "",
    email: "",
    description: "",
    mobile: "",
    facebook: "",
    x: "",
    linkedin: "",
    instagram: ""
})
const createBusiness = ()=>{
    form.post(route('my-businesses.create'), {
        onSuccess: (res) => {
            const message = res.props.message
            if (res.props.success) toastSuccess(message)
            else toastError(message)
            isOpen.value = false
            form.reset()
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
                    <DialogTitle>Add New Business</DialogTitle>
                    <DialogDescription>
                        Add New Business on Signboard
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="createBusiness" id="add-business" class="grid gap-4 py-4 overflow-y-auto px-6">
                    <InputText :form="form" label="Business Name" model="name" required/>
                    <InputText :form="form" label="Email Address" model="email" required/>
                    <InputText :form="form" label="Mobile Number" model="mobile" required/>
                    <InputText :form="form" label="Facebook" model="facebook" required/>
                    <InputText :form="form" label="Instagram" model="instagram" required/>
                    <InputText :form="form" label="X (Twitter)" model="x" required/>
                    <InputText :form="form" label="Linkedin" model="linkedin" required/>
                    <InputText label="Description" model="description" textarea required rows="5" :form="form"
                    />

                </form>
                <DialogFooter class="p-3">
                    <Button :disabled="form.processing" type="submit" form="add-business">
                        <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                        {{ form.processing ? 'Please wait...' : 'Add Business' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

</template>

<style scoped>

</style>
