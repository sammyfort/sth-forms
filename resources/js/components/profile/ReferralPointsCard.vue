<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { UserPen } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import EditPersonalDetailsDialog from '@/components/profile/EditPersonalDetailsDialog.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { copyContent } from '@/lib/helpers';

const page = usePage()
const user = computed(()=> page.props.auth.user)

</script>

<template>
    <Card class="mt-8 border shadow-none border-gray-200 rounded-2xl">
        <CardHeader class="text-lg font-black">Points</CardHeader>
        <CardContent>
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="text-2xl font-bold">{{ user.points }}</div>
                    <div>Points</div>
                    <div class="w-full italic text-sm text-secondary">
                        <div>
                            You can gain more points by referring others to join.
                        </div>
                        <div>
                            This points can be used to boost your services to reach more audience
                        </div>
                    </div>
                </div>
                <Button @click="copyContent(user.referral_link, 'Your referral code has been copied to clipboard')" class="rounded-full border px-8 h-11 py-3">
                    <div>Refer Now</div>
                </Button>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
