export async function jsonFetch(
    url: string,
    method: string = 'GET',
    json?: Object
) {

    const body = json ? JSON.stringify(json) : undefined;

    let headers: HeadersInit = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') as string,
    }

    const response = await fetch(url, {
        method,
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
