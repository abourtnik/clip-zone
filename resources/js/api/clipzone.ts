import {jsonFetch} from '@/functions/api'
import {
    VideoType,
    Paginator,
    InteractionType,
    InteractionsFilters,
    CommentType,
    CommentsSort,
    CommentData,
    ResponsesPaginator,
    Search,
    SearchModel,
    NotificationType,
    PlaylistType,
    PlaylistCreateData,
    PlaylistSaveData,
    ReportData
} from "@/types";

const API_URL =  '/api';

export async function getVideos(url: string, page: number = 1): Promise<Paginator<VideoType>> {
    return jsonFetch(url + `${url.includes('?') ? '&' : '?'}page=`+ page);
}

export async function subscribe(user_id: number): Promise<void> {
    return jsonFetch(API_URL + `/subscribe/${user_id}`, 'POST');
}

export async function interact(type: 'like' | 'dislike', id: number, model: 'App\\Models\\Video' | 'App\\Models\\Comment'): Promise<void> {
    return jsonFetch(API_URL + `/${type}`, 'POST', {
        'model': model,
        'id': id
    });
}

export async function getInteractions(video_id: number, filter: InteractionsFilters, search: string, page: number = 1): Promise<Paginator<InteractionType>> {
    return jsonFetch(API_URL + `/videos/${video_id}/interactions?filter=${filter}&search=${search}&page=`+ page);
}

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

export async function search(query: string): Promise<Search> {
    return jsonFetch(API_URL + '/search?q=' + query);
}

export async function searchModel(endpoint: string, query: string): Promise<SearchModel> {
    return jsonFetch(endpoint + '?q=' + query);
}

export async function getNotifications(page: number = 1): Promise<Paginator<NotificationType>> {
    return jsonFetch(API_URL + '/notifications?page=' + page);
}

export async function handleNotification(id: number, type: 'read' | 'unread'): Promise<void> {
    return jsonFetch(API_URL + `/notifications/${id}/${type}`);
}

export async function deleteNotification(id: number): Promise<void> {
    return jsonFetch(API_URL + `/notifications/${id}/delete`, 'DELETE');
}

export async function readAllNotifications(): Promise<void> {
    return jsonFetch(API_URL + `/notifications/read-all`);
}

export async function getPlaylistVideos(playlist_uuid: string | null, page: number = 1): Promise<Paginator<VideoType>> {
    return jsonFetch(API_URL + `/playlists/${playlist_uuid}/videos?page=`+ page);
}

export async function searchVideos(query: string, exceptIds: number[]): Promise<VideoType[]> {
    return jsonFetch(API_URL + `/search/videos?q=${query}`, 'POST', {
        except_ids: exceptIds
    });
}

export async function getUserPlaylists(user_id: number, page: number = 1, video_id: number): Promise<Paginator<PlaylistType>> {
    return jsonFetch(API_URL + `/users/${user_id}/playlists?page=${page}&video_id=${video_id}`);
}

export async function createPlaylist(data: PlaylistCreateData): Promise<PlaylistType> {
    return jsonFetch(API_URL + `/playlists`, 'POST', data);
}

export async function savePlaylist(data: PlaylistSaveData): Promise<void> {
    return jsonFetch(API_URL + `/playlists/save`, 'POST', data);
}

export async function report(data: ReportData): Promise<void> {
    return jsonFetch(API_URL + `/report`, 'POST', data);
}
