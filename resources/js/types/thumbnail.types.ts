export type ThumbnailType = {
    id: number,
    url: string,
    status: number,
    is_active: boolean,
}

export const ThumbnailStatus = {
    PENDING: 0,
    GENERATED: 1,
    ERROR: 2,
    UPLOADED: 3,
} as const
