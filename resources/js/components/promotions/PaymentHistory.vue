<script setup lang="ts">
import { ref, computed } from 'vue';
import { Calendar, CheckCircle, Clock, CreditCard, XCircle, Search, X } from 'lucide-vue-next';
import { PromotionI } from '@/types';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { dateAndTime, number_format } from '@/lib/helpers';

const props = defineProps<{
    promotions: PromotionI[],
}>()


const searchQuery = ref('');
const dateFilter = ref('all');


const filteredTransactions = computed<PromotionI[]>(() => {
    let filtered = [...props.promotions];

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(promotion =>
            promotion.payment_reference?.toLowerCase()?.includes(query) ||
            promotion.amount?.toString()?.includes(query) ||
            promotion.payment_platform?.toLowerCase()?.includes(query) ||
            promotion.payment_channel?.toLowerCase()?.includes(query) ||
            promotion.plan.name?.toLowerCase()?.includes(query)
        );
    }

    if (dateFilter.value !== 'all') {
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

        filtered = filtered.filter(promotion => {
            const transactionDate = new Date(promotion.created_at);

            switch (dateFilter.value) {
                case 'today':
                    return transactionDate >= today;
                case 'week':
                    const weekAgo = new Date(today);
                    weekAgo.setDate(weekAgo.getDate() - 7);
                    return transactionDate >= weekAgo;
                case 'month':
                    const monthAgo = new Date(today);
                    monthAgo.setMonth(monthAgo.getMonth() - 1);
                    return transactionDate >= monthAgo;
                default:
                    return true;
            }
        });
    }

    return filtered;
});


const clearFilters = () => {
    searchQuery.value = '';
    dateFilter.value = 'all';
};

const hasActiveFilters = computed(() => {
    return searchQuery.value !== '' || dateFilter.value !== 'all';
});

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'paid': return CheckCircle;
        case 'pending': return Clock;
        case 'failed': return XCircle;
        default: return Clock;
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'paid': return 'text-green-600 bg-green-50 border-green-200';
        case 'pending': return 'text-yellow-600 bg-yellow-50 border-yellow-200';
        case 'failed': return 'text-red-600 bg-red-50 border-red-200';
        default: return 'text-gray-600 bg-gray-50 border-gray-200';
    }
};
</script>

<template>
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xl mt-4">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="flex items-center gap-2 text-2xl font-bold text-gray-900">
                <CreditCard class="h-6 w-6 text-primary" />
                Promotion History
            </h2>
            <Badge v-if="promotions.length" variant="secondary" class="text-sm">
                {{ filteredTransactions.length }} of {{ promotions.length }} promotions
            </Badge>
        </div>

        <div class="mb-6 space-y-2">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                    <Input
                        v-model="searchQuery"
                        placeholder="Search reference, amount, platform, or channel..."
                        class="pl-10 w-100"
                    />
                </div>
                <Select v-model="dateFilter">
                    <SelectTrigger class="w-full sm:w-40">
                        <SelectValue placeholder="Filter by date" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="all">All Time</SelectItem>
                        <SelectItem value="today">Today</SelectItem>
                        <SelectItem value="week">Last Week</SelectItem>
                        <SelectItem value="month">Last Month</SelectItem>
                    </SelectContent>
                </Select>
                <Button
                    v-if="hasActiveFilters"
                    variant="outline"
                    size="sm"
                    @click="clearFilters"
                    class="flex items-center gap-1 whitespace-nowrap"
                >
                    <X class="h-3 w-3" />
                    Clear Filters
                </Button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-left">Date</TableHead>
                        <TableHead class="text-left">Plan</TableHead>
                        <TableHead class="text-left">Start Date</TableHead>
                        <TableHead class="text-left">End Date</TableHead>
                        <TableHead class="text-left">Status</TableHead>
                        <TableHead class="text-left">Amount</TableHead>
                        <TableHead class="text-left">Payment Status</TableHead>
                        <TableHead class="text-left">Reference</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="promotion in filteredTransactions" :key="promotion.id">
                        <TableCell>
                            <div class="flex items-center gap-1.5">
                                <Calendar class="h-4 w-4 text-gray-400" />
                                {{ dateAndTime(promotion.created_at) }}
                            </div>
                        </TableCell>
                        <TableCell class="flex flex-col gap-2">
                            <span class="font-semibold">{{ promotion.plan.name }}</span>
                            <Badge variant="outline" class="text-xs">
                                 <span>{{ promotion.plan.number_of_days }} days</span>
                            </Badge>
                        </TableCell>
                        <TableCell>{{ promotion.payment_status == 'paid' ? dateAndTime(promotion.starts_at) : '' }}</TableCell>
                        <TableCell>{{ promotion.payment_status == 'paid' ? dateAndTime(promotion.ends_at) : '' }}</TableCell>
                        <TableCell>
                            <Badge v-if="promotion.payment_status === 'paid' " :variant="promotion.is_active ? 'success' : 'destructive' ">
                                {{ promotion.is_active ? 'Active' : 'Expired' }}
                            </Badge>
                        </TableCell>
                        <TableCell>
                            <div class="flex items-center gap-1">
                                <span class="font-medium">₵{{ number_format(promotion.amount, 2) }}</span>
                            </div>
                        </TableCell>
                        <TableCell>
                            <Badge :class="['inline-flex items-center gap-1 text-xs font-medium capitalize', getStatusColor(promotion.payment_status)]">
                                <component :is="getStatusIcon(promotion.payment_status)" class="h-3 w-3" />
                                {{ promotion.payment_status }}
                            </Badge>
                        </TableCell>
                        <TableCell class="font-mono text-gray-500">{{ promotion.payment_reference }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div v-if="filteredTransactions.length === 0 && promotions.length > 0" class="text-center py-8">
            <Search class="h-12 w-12 text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500">No promotion match your filters</p>
            <Button variant="outline" size="sm" @click="clearFilters" class="mt-2">
                Clear Filters
            </Button>
        </div>

        <div v-if="promotions.length === 0" class="text-center py-8">
            <CreditCard class="h-12 w-12 text-gray-300 mx-auto mb-4" />
            <p class="text-gray-500">No promotion found</p>
        </div>
    </div>
</template>

<style scoped>
</style>
