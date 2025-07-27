<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { User } from '@/types';

defineProps<{
    referrals: User[]
}>()

</script>

<template>
    <Card class="mt-8 border shadow-none border-gray-200 rounded-2xl">
        <CardHeader class="text-lg font-black">Lis Of Users You Have Referred</CardHeader>
        <CardContent>
            <div class="overflow-x-auto">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-left">Name</TableHead>
                            <TableHead class="text-left">Mobile</TableHead>
                            <TableHead class="text-left">Join On</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="referral in referrals" :key="`ref-${referral.id}`">
                            <TableCell class="py-4">{{ referral.fullname }}</TableCell>
                            <TableCell class="py-4">{{ referral.mobile }}</TableCell>
                            <TableCell class="py-4">{{ referral.created_at_dh }}</TableCell>
                        </TableRow>
                    </TableBody>
                    <TableCaption v-if="!referrals.length" class="font-semibold">You have no referrals</TableCaption>
                </Table>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
