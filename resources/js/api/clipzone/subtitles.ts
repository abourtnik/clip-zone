import {SubtitleData, SubtitleType} from "@/types";
import {jsonFetch, toFormData} from "@/functions/api";
import {API_URL} from "./config";

export async function getSubtitles(video_uuid: string): Promise<{data: SubtitleType[]}> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/subtitles`);
}

export async function addSubtitle(video_uuid: string, data: SubtitleData): Promise<SubtitleType> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/subtitles`, 'POST', toFormData(data));
}

export async function updateSubtitle(video_uuid: string, subtitle_id: number, data: SubtitleData): Promise<SubtitleType> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/subtitles/${subtitle_id}`, 'PUT', toFormData(data));
}

export async function toggleSubtitle(video_uuid: string, subtitle_id: number): Promise<SubtitleType> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/subtitles/${subtitle_id}/toggle`, 'PATCH');
}

export async function deleteSubtitle(video_uuid: string, subtitle_id: number): Promise<void> {
    return jsonFetch(API_URL + `/videos/${video_uuid}/subtitles/${subtitle_id}`, 'DELETE');
}
