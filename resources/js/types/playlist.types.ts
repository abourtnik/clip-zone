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

export const PlaylistCreateDataSchema = z.object({
    title: z.string().min(1).max(150),
    status: z.enum(['0', '1', '2']),
});

export const PlaylistSaveDataSchema = z.object({
    video_id: z.coerce.number(),
    playlists: z.array(
        z.object({
            id: z.coerce.number(),
            checked: z.boolean()
        })
    ).min(1)
});

export type PlaylistCreateData = z.infer<typeof PlaylistCreateDataSchema>
export type PlaylistSaveData = z.infer<typeof PlaylistSaveDataSchema>
