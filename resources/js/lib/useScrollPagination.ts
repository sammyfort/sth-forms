import { router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

export function useScrollPagination<T>({ initialData, nextPageUrl, extractResponseData, preserveKeys = ['signboardsData'] }) {
    const items = ref<T[]>(initialData);
    const loading = ref(false);
    const nextPage = ref<string | null>(nextPageUrl);

    const handleScroll = () => {
        if (loading.value || !nextPage.value) return;

        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
            fetchMore();
        }
    };

    const fetchMore = () => {
        router.get(
            nextPage.value!,
            {},
            {
                preserveState: true,
                preserveScroll: true,
                preserveUrl: true,
                only: preserveKeys,
                onStart: () => (loading.value = true),
                onFinish: () => (loading.value = false),
                onSuccess: (page) => {
                    const response = extractResponseData(page);
                    items.value = [...items.value, ...response.data];
                    nextPage.value = response.next_page_url;
                },
            },
        );
    };

    onMounted(() => {
        window.addEventListener('scroll', handleScroll);
    });

    onUnmounted(() => {
        window.removeEventListener('scroll', handleScroll);
    });

    return { items, loading, nextPage, fetchMore };
}
