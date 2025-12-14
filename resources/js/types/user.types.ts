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
