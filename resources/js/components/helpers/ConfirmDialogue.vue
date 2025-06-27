
<script setup lang="ts">
import { ref, watchEffect } from 'vue'
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog'


const props = defineProps<{
    open: boolean
    title?: string
    description?: string
    confirmText?: string
    cancelText?: string
    loading?: boolean
}>()

const emits = defineEmits<{
    (e: 'update:open', value: boolean): void
    (e: 'confirm'): void
}>()
const isOpen = ref(props.open)

watchEffect(() => {
    isOpen.value = props.open
})

watchEffect(() => {
    emits('update:open', isOpen.value)
})
</script>

<template>
    <AlertDialog v-model:open="isOpen">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ props.title || 'Are you sure?' }}</AlertDialogTitle>
                <AlertDialogDescription>{{ props.description || 'This action cannot be undone.' }}</AlertDialogDescription>
            </AlertDialogHeader>

            <AlertDialogFooter>
                <AlertDialogCancel :disabled="props.loading">
                    {{ props.cancelText || 'Cancel' }}
                </AlertDialogCancel>
                <AlertDialogAction :disabled="props.loading" @click="$emit('confirm')">
                    {{ props.confirmText || 'Confirm' }}
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
