<script setup lang="ts">
import { useSlots } from 'vue'
import { Button } from '@/components/ui/button'
import { Check, LoaderCircle } from 'lucide-vue-next'

const props = defineProps<{
    form: any
    submitText?: string
    processingText?: string
    containerWidth?: string
    readyForSubmit?: boolean
}>()

const emit = defineEmits<{
    (e: 'submit'): void
}>()

const slots = useSlots()
const hasMedia = !!slots['media-section']
</script>

<template>
    <div :class="[containerWidth ?? 'max-w-5xl', 'mx-auto px-4 sm:px-6 lg:px-8 py-8']">
        <div
            :class="[
        'gap-8',
        hasMedia ? 'grid grid-cols-1 lg:grid-cols-3' : 'flex justify-center'
      ]"
        >
            <!-- Media slot only if provided -->
            <div v-if="hasMedia" class="order-1 lg:order-2 space-y-6">
                <slot name="media-section" />
            </div>

            <!-- Form sections -->
            <div
                :class="[
          'space-y-6',
          hasMedia ? 'order-2 lg:order-1 lg:col-span-2' : 'w-full lg:w-2/3'
        ]"
            >
                <slot name="form-sections" />

                <!-- Submit review box -->
                <div
                    v-if="readyForSubmit"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            Review your information and submit when ready
                        </p>
                        <Button
                            :disabled="props.form.processing"
                            @click="emit('submit')"
                            class="px-8 py-3 bg-gradient-to-r from-primary to-primary hover:from-primary
              hover:to-orange-300 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl
              transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <LoaderCircle
                                v-if="props.form.processing"
                                class="mr-2 h-5 w-5 animate-spin"
                            />
                            <Check v-else class="mr-2 h-5 w-5" />
                            {{ props.form.processing ? processingText : submitText }}
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
