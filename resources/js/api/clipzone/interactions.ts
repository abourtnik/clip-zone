import {jsonFetch} from "@/functions/api";
import {InteractionsFilters, InteractionType, InteractionsModel, Paginator} from "@/types";
import {API_URL} from "./config";

export async function interact(type: 'like' | 'dislike', id: number, model: InteractionsModel): Promise<void> {
    return jsonFetch(API_URL + `/${type}`, 'POST', {
        'model': model,
        'id': id
    });
}

export async function getInteractions(video_id: number, filter: InteractionsFilters, search: string, page: number = 1): Promise<Paginator<InteractionType>> {
    return jsonFetch(API_URL + `/videos/${video_id}/interactions?filter=${filter}&search=${search}&page=`+ page);
}

