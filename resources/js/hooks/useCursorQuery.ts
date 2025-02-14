import {InfiniteData, useInfiniteQuery, UseInfiniteQueryResult} from "@tanstack/react-query";
import {CursorPaginator} from "@/types";
import {useInView} from "react-intersection-observer";
import {useEffect} from "preact/hooks";

type Options<TData> = {
    key: string[],
    fetchFn: ({pageParam} : {pageParam: string | null}) => Promise<CursorPaginator<TData>>,
}

type UseCursorQueryResult<TData> = UseInfiniteQueryResult<InfiniteData<CursorPaginator<TData>>> & {
    ref: (node?: Element | null) => void;
};

export function useCursorQuery<TData = unknown> ({key, fetchFn} : Options<TData>) : UseCursorQueryResult<TData> {

    const { ref, inView} = useInView()

    const query = useInfiniteQuery({
        queryKey: key,
        queryFn: fetchFn,
        initialPageParam: null,
        getNextPageParam: (lastPage) => lastPage.meta.next_cursor ?? undefined,
    });

    useEffect( () => {
        if (inView && !query.isFetchingNextPage && !query.isError) {
            query.fetchNextPage()
        }
    }, [inView]);

    return {
        ...query,
        ref
    }
}
