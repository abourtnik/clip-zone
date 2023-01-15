import {useCallback, useState} from 'preact/hooks';

export function usePaginateFetch (url) {

    const [loading, setLoading] = useState(false);
    const [items, setItems] = useState([]);
    const [count, setCount] = useState([]);
    const [next, setNext] = useState(null);
    const load = useCallback(async (sort) => {
        setLoading(true);
        const response = await fetch((next || url) + (sort ? '&sort=' + sort : ''), {
            headers: {
                'Accept': 'application/json'
            },
        })
        setLoading(false);
        const data = await response.json();
        if (response.ok) {
            setItems(items => [...items, ...data.data])
            setCount(data.count ?? data.meta.total)
            setNext(data.links.next)
        } else {
            console.error(data);
        }
    }, [url, next])

    return {
        items,
        setItems,
        load,
        loading,
        count,
        setCount,
        hasMore: next !== null,
        setNext
    }
}

export function useToggle (initial) {

    const [value, setValue] = useState(initial);

    const toggle = () => setValue(value => !value)

    return [value, toggle]

}

export function useFetch(url, options = {}) {

    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(false);

    const load = () => {
        setError(false);
        setLoading(true);

        fetch(url, options)
            .then((res) => {
                if (res.ok) {
                    return res.json();
                } else {
                    throw new Error(`Error occured. Status: ${res.status}`);
                }
            })
            .then(res => setData(res))
            .catch(() => setError(true))
            .finally(() => setLoading(false))
    }

    return [data, loading, error, load];
}
