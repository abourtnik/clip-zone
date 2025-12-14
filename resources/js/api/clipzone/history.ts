import {HistoryType} from "@/types";
import {jsonFetch} from "@/functions/api";
import {API_URL} from "./config";

export async function getMyHistory(): Promise<HistoryType> {
    return jsonFetch(API_URL + `/me/history`);
}

export async function removeFromHistory(view_id: number): Promise<void> {
    return jsonFetch(API_URL + `/me/history/${view_id}`, 'DELETE');
}

export async function clearHistoryDay(date: Date): Promise<void> {
    return jsonFetch(API_URL + `/me/history/clear-day`, 'POST', {date});
}

export async function clearHistoryAll(): Promise<void> {
    return jsonFetch(API_URL + `/me/history/clear-all`, 'POST');
}
