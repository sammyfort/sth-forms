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
import InputSelect from '@/components/InputSelect.vue';

const isOpen = ref(false);

const props = defineProps<{
    signboard: {
        id: number;
        business_id: number,
        region_id: number,
        slug: string;
        town: string;
        street: string;
        landmark: string;
        blk_number: string;
        gps: string;
    };
    businesses: Array<{ label: string; value: string }>;
    regions: Array<{ label: string; value: string }>;
}>();

const form = useForm({
    business_id: String(props.signboard.business_id),
    region_id: props.signboard.region_id,
    town: props.signboard.town,
    street: props.signboard.street,
    landmark: props.signboard.landmark,
    blk_number: props.signboard.blk_number,
    gps: props.signboard.gps

});

const updateBusiness = () => {
    form.put(route('my-signboards.update', props.signboard.id), {
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
                    Update signboard information below and click save.
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="updateBusiness" id="edit-signboard-form" class="grid gap-4 py-4">
                <InputSelect label="Select Business" :form="form" model="business_id" :options="props.businesses" required disabled />
                <InputSelect label="Region" :form="form" model="region_id" :options="props.regions" required />
                <InputText :form="form" label="Town" model="town"  required />
                <InputText :form="form" label="Street" model="street" />
                <InputText :form="form" label="Landmark" model="landmark" required/>
                <InputText :form="form" label="Blk Number" model="blk_number" />
                <InputText :form="form" label="GPS" model="gps" />
            </form>
            <DialogFooter>
                <Button :disabled="form.processing" type="submit" form="edit-signboard-form">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ form.processing ? 'Please wait...' : 'Save Changes' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
