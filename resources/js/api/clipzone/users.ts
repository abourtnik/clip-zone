import {jsonFetch} from "@/functions/api";
import {API_URL} from "./config";

export async function subscribe(user_id: number): Promise<void> {
    return jsonFetch(API_URL + `/subscribe/${user_id}`, 'POST');
}
