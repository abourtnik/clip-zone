import {Search, SearchModel} from "@/types";
import {jsonFetch} from "@/functions/api";
import {API_URL} from "./config";

export async function search(query: string): Promise<Search> {
    return jsonFetch(API_URL + '/search?q=' + query);
}

export async function searchModel(endpoint: string, query: string): Promise<SearchModel> {
    return jsonFetch(endpoint + '?q=' + query);
}
