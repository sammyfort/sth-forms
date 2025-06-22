<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { SquarePen, Upload } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { toastError, toastSuccess } from '@/lib/helpers';

const profileImageRef = ref();
const avatar = ref<string>('');
const avatarForm = useForm({
    avatar: null,
});
const hasSelectedAvatar = ref<boolean>(false);
const page = usePage();
const user = computed(() => page.props.auth.user);

const setSelectedAvatar = (e) => {
    const file = e.target.files[0];
    if (file) {
        avatar.value = URL.createObjectURL(file);
        avatarForm.avatar = file;
        hasSelectedAvatar.value = true;
    }
};

const submitAvatar = () => {
    avatarForm.post(route('profile.edit-avatar'), {
        onSuccess: (res) => {
            const message = res.props.message;
            if (res.props.success) {
                toastSuccess(message);
                hasSelectedAvatar.value = false;
            } else toastError(message);
        },
        preserveScroll: true,
    });
};

const setAttachmentFromURL = async (url: string) => {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Failed to fetch file: ${response.statusText}`);
        }
        const urlObj = new URL(url);
        const fileName = urlObj.pathname.split('/').pop();
        const blob = await response.blob();
        const file = new File([blob], fileName as string, { type: blob.type });
        avatar.value = URL.createObjectURL(file);
    } catch (error) {
        console.error('Error fetching file from URL:', error);
    }
};

onMounted(() => {
    if (user.value.avatar) {
        setAttachmentFromURL(user.value.avatar.original_url);
    }
});
</script>

<template>
    <div class="relative" :class="{'mb-5': avatarForm.errors.avatar}">
        <Avatar class="h-25 w-25">
            <a v-if="!hasSelectedAvatar && user.avatar && !avatarForm.avatar" :href="user.avatar.original_url" class="glightbox cursor-pointer">
                <AvatarImage :src="avatar" />
            </a>
            <AvatarImage v-else :src="avatar" />
            <AvatarFallback>{{ user.initials }}</AvatarFallback>
        </Avatar>
        <div class="absolute top-[-2px] right-[-2px]">
            <SquarePen @click="profileImageRef.click()" :size="25" class="cursor-pointer" />
            <input @change="setSelectedAvatar" type="file" class="hidden" ref="profileImageRef" />
        </div>
        <div v-show="hasSelectedAvatar">
            <Button :processing="avatarForm.processing" @click="submitAvatar" size="sm" class="mt-3"> <Upload /> Save Avatar </Button>
            <InputError :message="avatarForm.errors.avatar" class="absolute w-full whitespace-nowrap mt-2" />
        </div>
    </div>
</template>

<style scoped></style>
