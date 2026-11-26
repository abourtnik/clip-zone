import {SubtitleShowType, UserType} from "@/types";
import {z} from "zod";
import i18n from "i18next";
import {formatSizeUnits, MB} from "@/functions/size";
import {mimeToLabel} from "@/functions/string";

export type VideoType = {
    id: number,
    uuid: string,
    title: string,
    short_title : string,
    thumbnail: string,
    formated_duration: string,
    views: number,
    route: string,
    published_at: string | number,
    user: UserType
    is_private?: boolean
}

export type VideoPlayerType = {
    thumbnail: string,
    file: string,
    show_ad: boolean,
    subtitles: SubtitleShowType[],
}

export type VideosSort = 'latest' | 'popular' | 'oldest';

export enum VideoStatus {
    PUBLIC = 0,
    PRIVATE = 1,
    PLANNED = 2,
    UNLISTED = 3,
    DRAFT = 4,
    BANNED = 5,
    FAILED = 6,
}

const acceptedFormats = {
    "video/mp4": "MP4",
    "video/ogg": "OGG",
    "video/webm": "WEBM",
    "video/quicktime": "MOV",
    "video/x-msvideo": "AVI",
    "video/x-ms-asf": "WMV",
    "application/x-shockwave-flash": "SWF",
    "video/mpeg": "MPEG",
    "video/x-flv": "FLV",
    "video/x-matroska": "MKV",
} as Record<string, string>;

export const createVideoSchema = (maxSize: number) => {
    return z
        .file()
        .max(
            maxSize,
            {
                error: (iss) => {
                    return i18n.t(
                        "Your file is too large ({size} MB) The uploading file should not exceed {maxSize}",
                        {
                            size: iss.input instanceof File
                                ? (iss.input.size / MB).toFixed(2)
                                : "unknown",
                            maxSize: formatSizeUnits(maxSize),
                        }
                    )
                }
            }
        )
        .mime(
            Object.keys(acceptedFormats),
            {
                error: (iss) => {
                    return i18n.t(
                        "Unsupported format ({type}). Accepted formats: {allowedTypes}",
                        {
                            type: typeof iss.input === "string" ? mimeToLabel(iss.input) : "unknown",
                            allowedTypes: new Intl.ListFormat(i18n.language, { style: "long", type: "conjunction" }).format(
                                Object.values(acceptedFormats)
                            )
                        }
                    )
                }
            }
        )
}
