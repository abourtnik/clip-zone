import {z} from "zod";
import i18n from 'i18next';
import configuration from "@/config";
import {mimeToLabel} from "@/functions/string";

export type ImageKind = keyof typeof configuration;

const MB = 1024 * 1024;

const acceptedFormats = {
    "image/jpeg": "JPG",
    "image/png": "PNG",
    "image/webp": "WebP",
} as Record<string, string>;

const getImageSize = async (file: File) => {
    const bitmap = await createImageBitmap(file);
    const size = { width: bitmap.width, height: bitmap.height };
    bitmap.close();
    return size;
};

export const createImageSchema = (kind: ImageKind) => {

    const { maxSize, minWidth, minHeight } = configuration[kind];

    return z
        .file()
        .max(
            maxSize * MB,
            {
                error: (iss) => {
                    return i18n.t(
                        "Your file is too large ({size} MB) The uploading file should not exceed {maxSize} MB",
                        {
                            size: iss.input instanceof File
                                ? (iss.input.size / MB).toFixed(2)
                                : "unknown",
                            maxSize: maxSize,
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
        .superRefine(async (file, ctx) => {
            if (file.type === "image/svg+xml") return;

            const { width, height } = await getImageSize(file);

            if (width < minWidth || height < minHeight) {
                ctx.addIssue({
                    code: "custom",
                    message: i18n.t(
                        "Your image is too small ({width} x {height}). The uploading image must be at least {minWidth} x {minHeight} pixels",
                        { width, height, minWidth, minHeight }
                    ),
                });
            }
        })
}

