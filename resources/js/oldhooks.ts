import {useEffect} from 'preact/hooks';

export function useClickOutside (ref, callback) {
    useEffect(() => {
        const clickCallback = e => {
            if (ref.current && !ref.current.contains(e.target)) {
                callback()
            }
        }
        document.addEventListener('click', clickCallback)
        return function cleanup () {
            document.removeEventListener('click', clickCallback)
        }
    }, [ref, callback])
}

export async function jsonFetch(url, options = {}) {

    const response = await fetch(url, {
        ...options,
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
    });

    if (response.status === 204) {
        return null
    }
    const data = await response.json();

    if (response.ok) {
        return data;
    } else {
        document.getElementById('toast-message').innerText = data?.message ?? 'Whoops! An error occurred. Please try again later.';
        new bootstrap.Toast(document.getElementById('toast')).show()
        throw new Error('Une erreur s est produite')
    }
}
