import {CursorPaginator, Paginator, PlaylistCreateData, PlaylistSaveData, PlaylistType, VideoType} from "@/types";
import {jsonFetch} from "@/functions/api";
import {API_URL} from "./config";

export async function getPlaylistVideos(playlist_uuid: string | null, page: number = 1): Promise<Paginator<VideoType>> {
    return jsonFetch(API_URL + `/playlists/${playlist_uuid}/videos?page=`+ page);
}
export async function getMyPlaylists(video_id: number, cursor: string | null): Promise<CursorPaginator<PlaylistType>> {
    return jsonFetch(API_URL + `/me/playlists?video_id=${video_id}` + (cursor ? `&cursor=` + cursor  : ''));
}

export async function createPlaylist(data: PlaylistCreateData): Promise<PlaylistType> {
    return jsonFetch(API_URL + `/playlists`, 'POST', data);
}

export async function savePlaylist(data: PlaylistSaveData): Promise<void> {
    return jsonFetch(API_URL + `/playlists/save`, 'POST', data);
}
