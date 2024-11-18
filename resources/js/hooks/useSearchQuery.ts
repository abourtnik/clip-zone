import {useDebounce} from "@/hooks/useDebounce";
import {useQuery} from "@tanstack/react-query";

type Options = {
    query: string,
    key: string,
    searchFn: Function,
    debounce?: number
}

export function useSearchQuery ({query, key, searchFn, debounce = 300} : Options) {

    const debouncedSearchQuery = useDebounce(query, debounce);

    return useQuery({
        queryKey: [key, debouncedSearchQuery],
        queryFn: () => searchFn(debouncedSearchQuery),
        enabled: debouncedSearchQuery.trim().length > 0
    })

}