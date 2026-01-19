import {z} from "zod";

export enum ReportReason {
    SEXUAL_CONTENT = 0,
    VIOLENT_OR_REPULSIVE_CONTENT = 1,
    HATEFUL_OR_ABUSIVE_CONTENT = 2,
    HARASSMENT_OR_BULLYING = 3,
    HARMFUL_OR_DANGEROUS_ACTS = 4,
    MISINFORMATION = 5,
    CHILD_ABUSE = 6,
    PROMOTES_TERRORISM = 7,
    INFRINGES_MY_RIGHTS = 8,
    CAPTIONS_ISSUE = 9
}

export const ReportDataSchema = z.object({
    id: z.coerce.number(),
    type: z.enum(['App\\Models\\Video', 'App\\Models\\Comment', 'App\\Models\\User']),
    reason: z.coerce.number().pipe(z.nativeEnum(ReportReason)),
    comment: z.string().max(5000).optional(),
});

export type ReportData = z.infer<typeof ReportDataSchema>
