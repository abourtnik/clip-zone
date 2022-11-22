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
            setCount(data.meta.total)
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
