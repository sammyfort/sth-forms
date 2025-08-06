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

const editorRef = ref<HTMLElement | null>(null)
let quill: Quill | null = null

const initEditor = async () => {
    await nextTick()
    if (!editorRef.value) return

    quill = new Quill(editorRef.value, {
        theme: 'snow',
        readOnly: !!props.readonly,
        modules: {
            toolbar: props.readonly
                ? false
                : [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    [{ align: [] }],
                    ['blockquote', 'code-block'],
                    ['clean'],
                ],
        },
        placeholder: props.placeholder || '',
    })

    insertHTML(props.modelValue || '')

    quill.enable(!props.readonly)

    quill.on('text-change', () => {
        if (!props.readonly) {
            const newHtml = quill.root.innerHTML
            emit('update:modelValue', newHtml === '<p><br></p>' ? '' : newHtml)
        }
    })

    editorRef.value.addEventListener('click', () => {
        if (!props.readonly) quill?.focus()
    })
}

const insertHTML = (html: string) => {
    if (!quill) return
    if (!html || html === '<p><br></p>') {
        quill.setText('')
        return
    }
    quill.clipboard.dangerouslyPasteHTML(html, 'silent')
}

watch(
    () => props.modelValue,
    (newVal) => {
        if (quill && newVal !== quill.root.innerHTML) {
            insertHTML(newVal || '')
        }
    }
)

onMounted(initEditor)
onBeforeUnmount(() => {
    if (quill) {
        quill.off('text-change')
        quill = null
    }
})

defineExpose({
    focus: () => quill?.focus(),
    blur: () => quill?.blur(),
    getHTML: () => quill?.root.innerHTML || ''
})
</script>

<template>
    <div class="quill-editor-wrapper" :class="{ 'readonly': readonly }">
        <div
            ref="editorRef"
            class="min-h-[200px] w-full border rounded-md overflow-hidden cursor-text
                   [&_.ql-editor]:min-h-[200px] [&_.ql-editor]:cursor-text [&_.ql-container]:min-h-[200px]"
        ></div>
    </div>
</template>
