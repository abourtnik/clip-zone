import {VideoType} from "@/types";

export type HistoryType = {
    data: {
        date: Date;
        views: {
            id: number;
            video: VideoType;
        }[];
    }[];
};
