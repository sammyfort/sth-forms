<script setup lang="ts">

import { onMounted, ref } from 'vue';
import { ArrowUp } from "lucide-vue-next"
import { Button } from '@/components/ui/button';

const isVisible = ref<boolean>(false)
const toggleVisibility = () => {
    isVisible.value = window.scrollY > 200
};
const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
};

onMounted(()=>{
    window.addEventListener("scroll", toggleVisibility);
})
</script>

<template>
    <div class="fixed bottom-6 right-6 opacity-100 starting:opacity-0 transition-opacity duration-2000">
        <Button
            v-show="isVisible"
            @click="scrollToTop"
            class="p-5 rounded-full"
            size="icon"
            variant="secondary"
        >
            <ArrowUp className="w-5 h-5" />
        </Button>
    </div>
</template>

<style scoped>

</style>
