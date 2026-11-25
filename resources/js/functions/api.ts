export async function jsonFetch(url: string, method: string = 'GET', payload?: Object | FormData)
{
    const isFormData = payload instanceof FormData;
    const body = payload ? (isFormData ? payload : JSON.stringify(payload)) : undefined;

    let headers: HeadersInit = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') as string,
    }

    if (payload && !isFormData) {
        headers['Content-Type'] = 'application/json';
    }

    if (body instanceof FormData && method !== 'POST') {
        body.append('_method', method);
    }

    const response = await fetch(url, {
        method: isFormData ? 'POST' : method,
        body,
        headers: headers,
        credentials: 'include',
    });

    if (response.status === 204) {
        return null
    }

    const data = await response.json();

    if (response.ok) {
        return data;
    }

    throw new Error(data?.message ?? 'Oups something went wrong !')
}

export function toFormData(data: Object): FormData {
    const formData = new FormData();

    for (const [key, value] of Object.entries(data)) {

        if (value === undefined || value === null) continue;

        if (typeof value === 'boolean') {
            formData.append(key, value ? "1" : "0");
            continue;
        }

        formData.append(key, value);
    }

    return formData;
}
