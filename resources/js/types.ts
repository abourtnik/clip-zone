export type VideoType = {
    id: number,
    uuid: string,
    title: string,
    short_title : string
    thumbnail: string,
    formated_duration: string
    views: number,
    route: string
    user: UserType,
    publication_date: Date
}

export type UserType = {
    id: number,
    username : string,
    slug: string,
    avatar: string,
    route: string
    show_subscribers: boolean
    subscribers?: number
    videos_count: number
    created_at: Date,
}

export type CommentType = {
    id: number,
    parsed_content: string,
    created_at: string
    user: UserType,
}

export type UserVideosSort = 'latest' | 'popular' | 'oldest'

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
    }
}
export type SearchResultType = VideoType

export type Search = {
    total: number,
    items: SearchResultType[]
}
