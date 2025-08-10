<script setup lang="ts">
const emit = defineEmits<{
    'update:modelValue': [value: string]
    'focusEditor': []
}>()

// Full HTML template content
const jobTemplate = `
<h3><strong>About Company</strong></h3>
<p>provide brief information about the company employing...</p> <br>

<h3><strong>About the Role</strong></h3>
<p>provide information about the role...</p> <br>

<h3><strong>Responsibilities:</strong></h3>
<ul>
  <li>list responsibilities of the successful employee...</li>
</ul> <br>

<h3><strong>Skills and Qualifications/Experience:</strong></h3>
<ul>
  <li>list skills and qualifications expected from applicant...</li>
</ul> <br>

<h3><strong>Preferred Qualifications:</strong></h3>
<ul>
  <li>list nice to have qualifications...</li>
</ul> <br>

<h3><strong>Why Join Us?</strong></h3>
<ul>
    <li>provide something nice to attract candidate's attention...</li>
</ul>
`

const insertTemplate = () => {
    emit('update:modelValue', jobTemplate)
    emit('focusEditor')
}
</script>

<template>
    <button
        type="button"
        @click="insertTemplate"
        class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md mt-3"
    >
        Create from Template
    </button>
</template>
