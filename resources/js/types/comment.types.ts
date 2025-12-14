import {z} from "zod";
import {ResponsesPaginator} from "@/types";

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

export type CommentsSort = 'top' | 'newest'

export const CommentDataSchema = z.object({
    content: z.string().min(1).max(5000),
    parent_id: z.coerce.number().optional()
});

export type CommentData = z.infer<typeof CommentDataSchema>
