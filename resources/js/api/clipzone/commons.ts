import {jsonFetch} from "@/functions/api";

export async function input(endpoint: string, data: {}): Promise<{ [key: string]: string }> {
    return jsonFetch(endpoint, 'POST', data);
}
