import {z} from "zod";

export type VideoType = {
    id: number,
    uuid: string,
    title: string,
    short_title : string,
    thumbnail: string,
    formated_duration: string,
    views: number,
    route: string,
    publication_date: string | number,
    user: UserType
    is_private?: boolean
}

export type UserType = {
    id: number,
    username : string,
    slug: string,
    avatar: string,
    route: string
    show_subscribers: boolean
    subscribers?: number
    subscribed_by_auth_user?: boolean
    videos_count: number
    created_at: Date,
}

export type InteractionType = {
    id: number,
    status: boolean,
    user: UserType,
    perform_at: Date,
}

export type NotificationType = {
    id: number,
    message: string,
    url: string,
    is_read: boolean,
    created_at: Date
}

export type CommentType = {
    id: number,
    content: string,
    parsed_content: string,
    short_content: string,
    is_long: boolean,
    user: {
        id: number,
        username: string,
        avatar: string
        route: string,
        is_video_author: boolean
    },
    video_uuid: string,
    created_at: Date
    is_updated: boolean,
    likes_count: number,
    dislikes_count: number,
    liked_by_auth_user?: boolean,
    disliked_by_auth_user?: boolean,
    class: "App\\Models\\Comment"
    can_delete?: boolean,
    can_update?: boolean,
    can_report?: boolean,
    can_pin?: boolean,
    is_reply: boolean,
    parent_id?: number,
    is_pinned?: boolean,
    has_replies?: boolean,
    replies_count?: number,
    is_video_author_reply?: boolean,
    is_video_author_like?: boolean,
    video_author?: {
        username: string,
        avatar: string
    },
    reported_at?: string,
    replies?: ResponsesPaginator,
}

export const CommentDataSchema = z.object({
    content: z.string().min(1).max(5000),
    parent_id: z.coerce.number().optional()
})

export type CommentData = z.infer<typeof CommentDataSchema>

export type UserVideosSort = 'latest' | 'popular' | 'oldest'
export type InteractionsFilters = 'all' | 'up' | 'down'
export type CommentsSort = 'top' | 'newest'

export type Paginator<T> = {
    data: T[],
    links: {
        next: string
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

export type ResponsesPaginator = {
    data: CommentType[],
    next_page: number | null,
    total: number
}

export type SearchVideoType = {
    title: string,
    user: string
    views: string,
    formated_duration: string,
    publication_date: number,
    thumbnail: string,
    url: string,
    uuid: string,
    _formatted: {
        title: string,
        user: string,
        views: string,
        publication_date: string,
        thumbnail: string,
        url: string,
        uuid: string
    }
}

export type Search = {
    items: SearchVideoType[],
    route: string,
    total: number,
}

export type SearchModel = {
    data: {
        value: string,
        label: string
    }[]
}
