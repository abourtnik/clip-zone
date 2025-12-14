export type SearchVideoType = {
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
        views: string,
        published_at: string,
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
