import {jsonFetch} from '@/functions/api'
import {VideoType, Paginator} from "@/types";


const API_URL =  '/api';

export async function getVideos(url: string, page: number = 1): Promise<Paginator<VideoType>> {
    return jsonFetch(url + `${url.includes('?') ? '&' : '?'}page=`+ page);
}

export async function subscribe(user_id: number): Promise<void> {
    return jsonFetch(API_URL + `/subscribe/${user_id}`, 'POST');
}
