import { query } from '@vortechron/query-builder-ts';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

export function useFilterQuery(options: {
    form: any;
    routeName: string;
    preserveKeys: string[];
    buildQuery: (queryBuilder: any) => any;
}) {
    const filteredQuery = ref<string | null>(null);

    const runFilter = () => {
        const qBuilder = query();
        options.buildQuery(qBuilder);
        const built = qBuilder.build().replace('/', '');
        filteredQuery.value = built === '/' ? null : built;

        router.visit(
            route(options.routeName) + decodeURIComponent(filteredQuery.value || ''),
            {
                // preserveState: true,
                preserveScroll: true,
                only: options.preserveKeys,
                onStart: () => (options.form.processing = true),
                onFinish: () => (options.form.processing = false),
            },
        );
    };

    return { filteredQuery, runFilter };
}
