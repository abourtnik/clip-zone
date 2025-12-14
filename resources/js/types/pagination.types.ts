import {CommentType} from "@/types";

export type Paginator<T> = {
    data: T[],
    links: {
        next: string | null
    },
    meta: {
        current_page: number,
        from: number,
        last_page: number,
        per_page: number,
        to: number,
        total: number
    },
    count?: number
}

export type CursorPaginator<T> = {
    data: T[],
    meta: {
        path: string,
        per_page: number,
        next_cursor: string | null,
        prev_cursor: string | null,
    },
}

export type ResponsesPaginator = {
    data: CommentType[],
    next_page: number | null,
    total: number
}
