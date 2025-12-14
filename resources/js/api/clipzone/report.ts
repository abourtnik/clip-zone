import {ReportData} from "@/types";
import {jsonFetch} from "@/functions/api";
import {API_URL} from "./config";

export async function report(data: ReportData): Promise<void> {
    return jsonFetch(API_URL + `/report`, 'POST', data);
}
