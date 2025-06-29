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
import InputSelect from '@/components/InputSelect.vue';

const isOpen = ref(false)
const form = useForm({
    business_id: "",
    region_id: "",
    town: "",
    street: "",
    landmark: "",
    blk_number: "",
    gps: "",

})
const props = defineProps<{
    businesses: Array<{
        label: string;
        value: string
    }>;
    regions: Array<{
        label: string;
        value: string
    }>
}>()
const createBusiness = ()=>{
    form.post(route('my-signboards.create'), {
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
        <DialogContent class="sm:max-w-[600px] max-h-[90dvh] overflow-y-auto">
            <DialogHeader class="p-6 pb-0">
                <DialogTitle>Add New Signboard</DialogTitle>
                <DialogDescription>
                    Add New Signboard
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="createBusiness" id="add-signboard" class="grid gap-4 py-4">
                <InputSelect label="Select Business" :form="form" model="business_id" :options="props.businesses" required />
                <InputSelect label="Region" :form="form" model="region_id" :options="props.regions" required />
                <InputText :form="form" label="Town" model="town" required />
                <InputText :form="form" label="Street" model="street" />
                <InputText :form="form" label="Landmark" model="landmark" required/>
                <InputText :form="form" label="Blk Number" model="blk_number" />
                <InputText :form="form" label="GPS" model="gps" />
            </form>
            <DialogFooter class="p-3">
                <Button :disabled="form.processing" type="submit" form="add-signboard">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ form.processing ? 'Please wait...' : 'Add Business' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

</template>

<style scoped>

</style>
