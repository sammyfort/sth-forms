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

    const html = props.modelValue || '<p><br></p>'
    quill.root.innerHTML = html
    quill.enable(!props.readonly)

    quill.on('text-change', () => {
        if (!props.readonly) {
            const newHtml = quill.root.innerHTML
            emit('update:modelValue', newHtml === '<p><br></p>' ? '' : newHtml)
        }
    })
}

watch(
    () => props.modelValue,
    (newVal) => {
        if (quill && newVal !== quill.root.innerHTML) {
            quill.root.innerHTML = newVal || '<p><br></p>'
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
        <div ref="editorRef" class="min-h-[200px]"></div>
    </div>
</template>

<style scoped>

</style>
