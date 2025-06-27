<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import InputText from '@/components/InputText.vue';
import { useForm } from '@inertiajs/vue3';
import { toastError, toastSuccess } from '@/lib/helpers';
import { ref } from 'vue';
import { LoaderCircle } from 'lucide-vue-next';

const isOpen = ref(false);

const props = defineProps<{
    business: {
        id: number;
        name: string;
        email: string;
        mobile: string;
        description: string;
        facebook?: string;
        x?: string;
        linkedin?: string;
        instagram?: string;
    };
}>();

const form = useForm({
    name: props.business.name,
    email: props.business.email,
    mobile: props.business.mobile,
    description: props.business.description,
    facebook: props.business.facebook || '',
    x: props.business.x || '',
    linkedin: props.business.linkedin || '',
    instagram: props.business.instagram || ''
});

const updateBusiness = () => {
    form.put(route('my-businesses.update', props.business.id), {
        onSuccess: (res) => {
            const message = res.props.message;
            if (res.props.success) toastSuccess(message);
            else toastError(message);
            isOpen.value = false;
        },
        preserveScroll: true
    });
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[600px] max-h-[90dvh] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>Update Business</DialogTitle>
                <DialogDescription>
                    Update business information below and click save.
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="updateBusiness" id="edit-business-form" class="grid gap-4 py-4">
                <InputText :form="form" model="name" label="Business Name" required />
                <InputText :form="form" model="email" label="Email" type="email" required />
                <InputText :form="form" model="mobile" label="Mobile Number" type="tel" required />
                <InputText :form="form" model="description" label="Description" textarea required />
                <InputText :form="form" model="facebook" label="Facebook Link" />
                <InputText :form="form" model="x" label="X (Twitter) Link" />
                <InputText :form="form" model="linkedin" label="LinkedIn Link" />
                <InputText :form="form" model="instagram" label="Instagram Link" />
            </form>
            <DialogFooter>
                <Button :disabled="form.processing" type="submit" form="edit-business-form">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ form.processing ? 'Please wait...' : 'Save Changes' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
