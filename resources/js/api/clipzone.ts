import {jsonFetch} from '@/functions/api'
import {VideoType, Paginator, InteractionType, InteractionsFilters} from "@/types";

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
