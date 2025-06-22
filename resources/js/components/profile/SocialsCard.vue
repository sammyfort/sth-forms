<script setup lang="ts">
import { Facebook, Instagram, Linkedin, UserPen } from 'lucide-vue-next';
import { Card, CardContent } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Button } from '@/components/ui/button';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import EditSocialsDialog from '@/components/profile/EditSocialsDialog.vue';
import { toastInfo } from '@/lib/helpers';
import ProfileAvatar from '@/components/profile/ProfileAvatar.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);


const goToSocial = (url: string | null) => {
    if (!url) {
        toastInfo('Social not set !!');
        return;
    }
    window.open(url, 'blank');
};

</script>

<template>
    <Card class="rounded-2xl border border-gray-200 shadow-none">
        <CardContent>
            <div class="flex w-full flex-col items-center gap-4 lg:flex-row">
                <ProfileAvatar />
                <div>
                    <div class="text-lg font-semibold">{{ user.fullname }}</div>
                    <div class="text-fade flex h-5 items-center space-x-2 text-sm">
                        <div>{{ user.email }}</div>
                        <Separator orientation="vertical" />
                        <div>{{ user.mobile }}</div>
                    </div>
                </div>
                <div class="lg-w-full flex w-full flex-wrap items-center justify-center gap-3 lg:ms-auto lg:w-fit">
                    <button
                        @click="goToSocial(user.facebook)"
                        :class="{ 'border-green-500': user.facebook }"
                        class="shadow-theme-xs flex h-11 w-11 cursor-pointer items-center justify-center gap-2 rounded-full border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800"
                    >
                        <Facebook />
                    </button>
                    <button
                        @click="goToSocial(user.x)"
                        :class="{ 'border-green-500': user.x }"
                        class="shadow-theme-xs flex h-11 w-11 cursor-pointer items-center justify-center gap-2 rounded-full border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800"
                    >
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.1708 1.875H17.9274L11.9049 8.75833L18.9899 18.125H13.4424L9.09742 12.4442L4.12578 18.125H1.36745L7.80912 10.7625L1.01245 1.875H6.70078L10.6283 7.0675L15.1708 1.875ZM14.2033 16.475H15.7308L5.87078 3.43833H4.23162L14.2033 16.475Z"
                                fill=""
                            ></path>
                        </svg>
                    </button>
                    <button
                        @click="goToSocial(user.linkedin)"
                        :class="{ 'border-green-500': user.linkedin }"
                        class="shadow-theme-xs flex h-11 w-11 cursor-pointer items-center justify-center gap-2 rounded-full border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800"
                    >
                        <Linkedin />
                    </button>
                    <button
                        @click="goToSocial(user.instagram)"
                        :class="{ 'border-green-500': user.instagram }"
                        class="shadow-theme-xs flex h-11 w-11 cursor-pointer items-center justify-center gap-2 rounded-full border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800"
                    >
                        <Instagram />
                    </button>
                    <EditSocialsDialog>
                        <Button variant="secondary" class="flex h-11 w-full items-center justify-center gap-2 rounded-full border px-8 py-3 lg:w-fit">
                            <div>
                                <UserPen />
                            </div>
                            <div>Edit</div>
                        </Button>
                    </EditSocialsDialog>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped></style>
