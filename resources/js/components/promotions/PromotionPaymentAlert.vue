<script setup lang="ts">
import { Clock, XCircle, Check } from 'lucide-vue-next';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { PaymentStatusI } from '@/types';

defineProps<{
    payment_status:PaymentStatusI
}>()

</script>

<template>
    <Alert v-if="payment_status == 'success'" class="mb-5" variant="success">
        <Check />
        <AlertTitle>Payment Successful</AlertTitle>
        <AlertDescription>
            Your payment has been successfully confirmed. <br />
            Your promotion is now active and visible to potential customers.
        </AlertDescription>
    </Alert>
    <Alert v-if="payment_status == 'pending'" class="mb-5" variant="success">
        <Clock />
        <AlertTitle>Payment in Progress</AlertTitle>
        <AlertDescription>
            We've received your request and are currently verifying your payment. <br />
            This may take a few moments. Once confirmed, your promotion will be activated automatically.
        </AlertDescription>
    </Alert>
    <Alert v-if="payment_status == 'cancelled'" class="mb-5" variant="destructive">
        <XCircle />
        <AlertTitle>Transaction Cancelled</AlertTitle>
        <AlertDescription>
            You cancelled the payment process. <br />
            If this was unintentional, please try again to complete your promotion.
        </AlertDescription>
    </Alert>
</template>

<style scoped>

</style>
