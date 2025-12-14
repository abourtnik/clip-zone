import {UserType} from "@/types";

export type VideoType = {
    id: number,
    uuid: string,
    title: string,
    short_title : string,
    thumbnail: string,
    formated_duration: string,
    views: number,
    route: string,
    published_at: string | number,
    user: UserType
    is_private?: boolean
}

export type VideosSort = 'latest' | 'popular' | 'oldest'
