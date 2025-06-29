export const getApi = async (path: string) => {
    const data = {
        metadata: {}
    };

    try {
        const request = await fetch(route('api', path));
        const response = await request.json();

        if (response.success) {
            data.metadata = response.data;
        }
    } catch (e) {
        console.error(e);
    }

    return data;
};
