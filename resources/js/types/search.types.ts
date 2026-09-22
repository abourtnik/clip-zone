export type SearchVideoType = {
    type: 'video',
    title: string,
    user: string
    views: string,
    formated_duration: string,
    published_at: number,
    thumbnail: string,
    url: string,
    uuid: string,
    _formatted: {
        title: string,
        user: string,
    }
}

export type SearchUserType = {
    type: 'user',
    username: string,
    avatar: string
    url: string,
    subscribers: number,
    _formatted: {
        username: string,
    }
}

export type Search = {
    items: Array<SearchVideoType | SearchUserType>,
    route: string,
    total: number,
}

export type SearchModel = {
    data: {
        value: string,
        label: string
    }[]
}
