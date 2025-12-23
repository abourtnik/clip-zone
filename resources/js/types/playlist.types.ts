import {z} from "zod";

export type PlaylistType = {
    id: number,
    uuid: string,
    title: string,
    thumbnail?: string,
    icon: string,
    status: string,
    has_video?: boolean,
    videos_count?: number
}

export enum PlaylistStatus {
    PRIVATE = 0,
    UNLISTED = 1,
    PUBLIC = 2,
}

export const PlaylistCreateDataSchema = z.object({
    title: z.string().min(1).max(150),
    status: z.coerce.number().pipe(z.nativeEnum(PlaylistStatus)),
});

export const PlaylistSaveDataSchema = z.object({
    playlist_id: z.coerce.number(),
    video_id: z.coerce.number(),
});

export type PlaylistCreateData = z.infer<typeof PlaylistCreateDataSchema>
export type PlaylistSaveData = z.infer<typeof PlaylistSaveDataSchema>
