import {z} from "zod";

export const REPORT_REASONS = [
    "Sexual Content",
    "Violent or repulsive content",
    "Hateful or abusive content",
    "Harassment or bullying",
    "Harmful or dangerous acts",
    "Misinformation",
    "Child abuse",
    "Promotes terrorism",
    "Infringes my rights",
    "Captions issue"
] as const;

export const ReportDataSchema = z.object({
    id: z.coerce.number(),
    type: z.enum(['App\\Models\\Video', 'App\\Models\\Comment', 'App\\Models\\User']),
    reason: z.enum(REPORT_REASONS),
    comment: z.string().max(5000).optional(),
});

export type ReportData = z.infer<typeof ReportDataSchema>
