import {z} from "zod";
import i18n from 'i18next';
import {MB} from "@/functions/size";
import {mimeToLabel} from "@/functions/string";

export type SubtitleType = {
    id : number,
    video_id : string,
    name : string,
    language : string | null,
    file_url : string | null,
    file_name : string | null,
    status : number,
    is_generated : boolean,
    is_uploaded : boolean,
    is_enabled : boolean,
    is_empty : boolean,
    created_at : Date,
}

export type SubtitleShowType = {
    id : number,
    name : string,
    language : string,
    file_url : string,
}

export type SubtitleDraft = Partial<SubtitleType> & Pick<SubtitleType, 'video_id'> & {
    tempId?: string;
};

export const SubtitleStatus = {
    PENDING: 0,
    GENERATED: 1,
    ERROR: 2,
    UPLOADED: 3,
} as const

export const SubtitleLanguages = ['ar', 'en', 'fr', 'zh', 'nl', 'de', 'hi', 'it', 'ko', 'es', 'pt', 'ru'];

const acceptedFormats = {
    "text/vtt": "WebVTT",
    "text/plain": "TXT",
} as Record<string, string>;

const MAX_SIZE: number = 2;

export const SubtitleDataSchema = z.object({
    name: z.string().min(1).max(150),
    language: z.enum(SubtitleLanguages, {error: i18n.t('Invalid language')}),
    file: z.preprocess(
            (f) => (f instanceof File && f.size === 0 ? undefined : f),
            z.file()
                .max(
                    MAX_SIZE * MB,
                    {
                        error: (iss) => {
                            return i18n.t(
                                "Your file is too large ({size} MB) The uploading file should not exceed {maxSize} MB",
                                {
                                    size: iss.input instanceof File
                                        ? (iss.input.size / MB).toFixed(2)
                                        : "unknown",
                                    maxSize: MAX_SIZE,
                                }
                            )
                        }
                    })
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
                .optional()
            ),
    is_enabled: z.stringbool(),
});

export type SubtitleData = z.infer<typeof SubtitleDataSchema>
