
<script setup lang="ts">
import { ref, defineEmits } from 'vue'
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
    title?: string
    description?: string
    confirmText?: string
    cancelText?: string
    loading?: boolean
}>()
const isOpen = ref(false)
const emit = defineEmits(['confirm'])




</script>

<template>
    <AlertDialog v-model:open="isOpen">
        <AlertDialogTrigger as-child>

            <slot/>
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ props.title || 'Are you sure?' }}</AlertDialogTitle>
                <AlertDialogDescription>{{ props.description || 'This action cannot be undone.' }}</AlertDialogDescription>
            </AlertDialogHeader>

            <AlertDialogFooter>
                <AlertDialogCancel :disabled="props.loading">
                    {{ props.cancelText || 'Cancel' }}
                </AlertDialogCancel>
                <AlertDialogAction :disabled="props.loading" @click="emit('confirm')">
                    {{ props.confirmText || 'Confirm' }}
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
