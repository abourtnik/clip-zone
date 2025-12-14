import {CommentsSort, CommentType, Paginator, ResponsesPaginator, CommentData} from "@/types";
import {jsonFetch} from "@/functions/api";
import {API_URL} from "./config";

export async function getComments(video_uuid: string, page: number = 1, sort?: CommentsSort): Promise<Paginator<CommentType>> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/comments?sort=${sort}&page=`+ page);
}

export async function getCommentsReplies(video_uuid: string, comment_id: number, page: number = 1, sort?: CommentsSort): Promise<ResponsesPaginator> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/comments/${comment_id}/replies?sort=${sort}&page=`+ page);
}

export async function addComment(video_uuid: string, data: CommentData): Promise<CommentType> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/comments`, 'POST', data);
}

export async function updateComment(video_uuid: string, comment_id: number, data: CommentData): Promise<CommentType> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/comments/${comment_id}`, 'PUT', data);
}

export async function deleteComment(video_uuid: string, comment_id: number): Promise<void> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/comments/${comment_id}`, 'DELETE');
}

export async function pinComment(type: 'pin' | 'unpin', video_uuid: string, comment_id: number): Promise<void> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/comments/${comment_id}/${type}`, 'POST');
}
