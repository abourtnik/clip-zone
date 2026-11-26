import {z} from "zod";
import i18n from 'i18next';

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

export const SubtitleDataSchema = z.object({
    name: z.string().min(1).max(150),
    language: z.enum(SubtitleLanguages, {error: i18n.t('Invalid language')}),
    file: z.preprocess(
            (f) => (f instanceof File && f.size === 0 ? undefined : f),
            z.file()
                .max(
                    2 * 1024 * 1024,
                    {
                        error: (iss) => {
                            return i18n.t(
                                "Your file is too large ({size} MB) The uploading file should not exceed {maxSize} MB",
                                {
                                    size: iss?.input?.size ?? 'unknown',
                                    maxSize: 2,
                                }
                            )
                        }
                    })
                .mime(
                    ['text/vtt','text/plain'],
                    {
                        error: (iss) => {
                            return i18n.t(
                                "Your file type is invalid ({type}). Allowed types are {allowedTypes}",
                                {
                                    type: iss?.input ?? "unknown",
                                    allowedTypes: ["text/vtt", "text/plain"].join(", "),
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
