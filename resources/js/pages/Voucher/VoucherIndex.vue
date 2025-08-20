<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import InputText from '@/components/InputText.vue';
import { Check, LoaderCircle, TicketCheck } from 'lucide-vue-next';
import { Head, useForm } from '@inertiajs/vue3';

import { toastError, toastSuccess } from '@/lib/helpers';
import { Button } from '@/components/ui/button';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';
import { Category, PaymentStatusI } from '@/types';
import InputSelect from '@/components/InputSelect.vue';
import {onMounted, computed, ref} from 'vue'
import PaymentAlert from '@/pages/Voucher/PaymentAlert.vue';

const props = defineProps<{
    categories: Category[]

}>();
const form = useForm({
    email: '',
    phone: '',
    category_id: '',
    full_name: '',


});
onMounted(() => {
    if (!document.querySelector('script[src="https://js.paystack.co/v1/inline.js"]')) {
        const script = document.createElement('script')
        script.src = 'https://js.paystack.co/v1/inline.js'
        document.head.appendChild(script)
    }
})

const totalAmount = computed(() => {
    const selectedId = Number(form.category_id);
    return props.categories.find(c => c.id === selectedId)?.price ?? 0;
});

const payment_status = ref(<PaymentStatusI>(''))

const handlePayment = () => {
    const handler = window.PaystackPop.setup({
        key: 'pk_test_1de8a9f52077e10e50d5fdb87229372eca98d10e',
        email: form.email,
        amount: totalAmount.value * 100,
        currency: 'GHS',
        ref: `STHRC-${Math.floor(Math.random() * 1000000000 + 1)}`,
        metadata: {
            email: form.email,
            phone: form.phone,
            category_id: form.category_id,
            full_name: form.full_name
        },
        onClose: () => {
           toastError('Payment cancelled')
        },
        callback: (data: object) => {
            console.log(data)
            payment_status.value = data?.status
            toastSuccess('Payment successful')
            form.reset()
        }
    })

    handler.openIframe()
}
</script>

<template>
    <Head title="Buy Voucher" />
    <Layout>
        <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8" >
            <div class="space-y-8">
                <PageHeader title="Buy Voucher" subtitle="Purchase a voucher to apply.
                Voucher code will be sent to email and phone number provided below. " :icon="TicketCheck" />

                <PaymentAlert :payment_status="payment_status" />
                <!-- Form Card -->
                <div class="w-full bg-gray-50 rounded-xl">
                    <div class="rounded-xl border shadow-sm border-gray-200 bg-white p-6">
                        <h2 class="mb-6 text-center text-lg font-semibold text-gray-900">Buy Voucher</h2>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <InputText :form="form" label="Full Name" model="full_name" required />
                            <InputText :form="form" label="Email" model="email" required />
                            <InputText :form="form" label="Phone" model="phone" required />
                            <InputSelect :form="form" label="Category" model="category_id" :options="props.categories" required searchable />
                        </div>
                    </div>
                </div>

                <!-- Submit Card -->
                <div class="rounded-xl border border-gray-200 bg-white p-6">
                    <div class="flex flex-col items-center justify-center space-y-4 md:flex-row md:justify-between md:space-y-0">
                        <p class="text-center text-sm text-gray-600 md:text-left">
                            Review your information and continue
                            <span class="text-green-600" v-if="form.category_id"> to pay GHS {{ totalAmount }}</span>
                        </p>
                        <Button
                            @click.prevent="handlePayment"
                            :disabled="form.processing || !form.email || totalAmount < 1"
                            class="rounded-lg bg-gradient-to-r from-primary to-primary px-8 py-3
                            font-semibold text-white shadow-lg transition-all duration-200 hover:from-primary hover:to-orange-300
                             hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                            <Check v-else class="mr-2 h-5 w-5" />
                            {{ form.processing ? 'Please wait' : 'Pay Now' }}
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
