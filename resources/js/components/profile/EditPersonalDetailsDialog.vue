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
import CountryRegionSelector from '@/components/CountryRegionSelector.vue';
import { Label } from '@/components/ui/label';


const page = usePage()
const user = computed(()=> page.props.auth.user)
const isOpen = ref(false)

const form = useForm({
    firstname: "",
    lastname: "",
    country_id: "",
    mobile: "",
    email: "",
})

onMounted(()=>{
    form.firstname = user.value.firstname
    form.lastname = user.value.lastname
    form.country_id = user.value.country.id
    form.mobile = user.value.mobile
    form.email = user.value.email
})

const updateDetails = ()=>{
    form.patch(route('profile.edit-personal'), {
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
                <DialogTitle>Edit Personal Details</DialogTitle>
                <DialogDescription>
                    Make changes to your profile here. Click save when you're done.
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="updateDetails" id="update-form" class="grid gap-4 py-4 overflow-y-auto px-6">
                <InputText :form="form" label="First Name" model="firstname" required/>
                <InputText :form="form" label="Last Name" model="lastname" required/>
                <div class="flex flex-col gap-2">
                    <Label>Country</Label>
                    <CountryRegionSelector
                        :form="form"
                        country-model="country_id"
                    />
                </div>
                <InputText :form="form" label="Mobile Number" model="mobile" required/>
                <InputText :form="form" label="Email" model="email" required/>
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
