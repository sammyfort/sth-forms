<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue'
import Quill from 'quill'
import 'quill/dist/quill.snow.css'

const props = defineProps<{
    modelValue: string
    placeholder?: string
    readonly?: boolean
}>()

const emit = defineEmits<{
    'update:modelValue': [value: string]
}>()

const editorRef = ref<HTMLElement>()
let quill: Quill | null = null
let isUpdating = false

const initEditor = async () => {
    await nextTick()

    if (!editorRef.value) return

    quill = new Quill(editorRef.value, {
        theme: 'snow',
        readOnly: props.readonly || false,
        modules: {
            toolbar: props.readonly ? false : [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['clean']
            ]
        },
        formats: [
            'header', 'bold', 'italic', 'underline',
            'list', 'link', 'align',
            'blockquote', 'code-block'
        ],
        placeholder: props.placeholder || 'Enter description...'
    })

    if (props.modelValue) {
        const delta = quill.clipboard.convert(props.modelValue)
        quill.setContents(delta, 'silent')
    }

    quill.on('text-change', () => {
        if (!isUpdating && !props.readonly) {
            const html = quill?.root.innerHTML || ''
            if (html !== props.modelValue) {
                emit('update:modelValue', html === '<p><br></p>' ? '' : html)
            }
        }
    })
}
watch(
    () => props.modelValue,
    (newValue) => {
        if (quill && !isUpdating) {
            const currentHTML = quill.root.innerHTML
            const normalizedNew = newValue || '<p><br></p>'

            if (normalizedNew !== currentHTML) {
                isUpdating = true
                try {
                    if (newValue) {
                        const delta = quill.clipboard.convert(newValue)
                        quill.setContents(delta, 'silent')
                    } else {
                        quill.setText('', 'silent')
                    }
                } catch (error) {
                    console.warn('Error setting Quill content:', error)
                    quill.root.innerHTML = newValue || ''
                }
                isUpdating = false
            }
        }
    }
)
watch(
    () => props.readonly,
    (newReadonly) => {
        if (quill) {
            quill.enable(!newReadonly)
        }
    }
)

onMounted(() => {
    initEditor()
})

onBeforeUnmount(() => {
    if (quill) {
        quill.off('text-change')
        quill = null
    }
})

defineExpose({
    focus: () => quill?.focus(),
    blur: () => quill?.blur(),
    getLength: () => quill?.getLength() || 0,
    getText: () => quill?.getText() || '',
    getHTML: () => quill?.root.innerHTML || ''
})
</script>

<template>
    <div class="quill-editor-wrapper" :class="{ 'readonly': readonly }">
        <div ref="editorRef" class="min-h-[200px]"></div>
    </div>
</template>

<style scoped>

</style>
