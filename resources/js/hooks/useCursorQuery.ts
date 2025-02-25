import {InfiniteData, useInfiniteQuery, UseInfiniteQueryResult, UndefinedInitialDataInfiniteOptions, QueryKey} from "@tanstack/react-query";
import {CursorPaginator} from "@/types";
import {useInView} from "react-intersection-observer";
import {useEffect} from "preact/hooks";

type Options<TData> = Omit<UndefinedInitialDataInfiniteOptions<CursorPaginator<TData>, Error, InfiniteData<CursorPaginator<TData>>, QueryKey, string|null>,  "getNextPageParam" | "initialPageParam">

type UseCursorQueryResult<TData> = UseInfiniteQueryResult<InfiniteData<CursorPaginator<TData>>> & {
    ref: (node?: Element | null) => void;
};

export function useCursorQuery<TData = unknown> (options : Options<TData>) : UseCursorQueryResult<TData> {

    const { ref, inView} = useInView()

    const query = useInfiniteQuery({
        ...options,
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
