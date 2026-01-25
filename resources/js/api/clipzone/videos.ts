import {CursorPaginator, VideoType, ThumbnailType, VideoPlayerType} from "@/types";
import {jsonFetch} from "@/functions/api";
import {API_URL} from "./config";

export async function getVideos(url: string, cursor: string | null): Promise<CursorPaginator<VideoType>> {
    return jsonFetch(url + `${url.includes('?') ? '&' : '?'}` + (cursor ? `cursor=` + cursor  : ''));
}

export async function viewVideo(video_uuid: string): Promise<void> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/view`, 'POST');
}

export async function getThumbnails(video_id: number): Promise<{data: ThumbnailType[]}> {
    return jsonFetch(API_URL + `/videos/${video_id}/thumbnails`);
}

export async function searchVideos(query: string): Promise<{data: VideoType[]}> {
    return jsonFetch(API_URL + `/search/videos?q=${query}`);
}

export async function player(video_uuid: string): Promise<VideoPlayerType> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/player`);
}
