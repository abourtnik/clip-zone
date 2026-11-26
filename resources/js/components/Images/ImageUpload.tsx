import { useRef } from 'preact/hooks';
import configuration from "@/config";
import {show as showToast} from "@/functions/toast";
import type { TargetedEvent, InputHTMLAttributes } from "preact"
import {createImageSchema, ImageKind} from "@/types";

type Props = {
    name: string,
    config: ImageKind,
} & InputHTMLAttributes<HTMLInputElement>

export default function ImageUpload ({name, config, ...attributes} : Props) {

    const input = useRef<HTMLInputElement>(null);
    const button = useRef<HTMLButtonElement>(null);

    const fileConfig = configuration[config];

    const change = async (event: TargetedEvent<HTMLInputElement>) => {

        const file = event.currentTarget.files?.[0];

        event.currentTarget.value = '';

        if(!file) {
            return;
        }

        const validation = await createImageSchema(config).safeParseAsync(file);

        if (!validation.success) {
            showToast(validation.error.issues[0].message)
            return;
        }

        const image = new Image();
        document.getElementById('cropped_image')!.setAttribute('src', URL.createObjectURL(file));
        button.current!.click()
        image.src = URL.createObjectURL(file);
    }

    return (
        <>
            <input
                ref={input}
                type="file"
                accept="image/*"
                name={name}
                id={name}
                className={'opacity-0 w-100 h-100 cursor-pointer'}
                onChange={change}
                {...attributes}
            />
            <button
                ref={button}
                className="d-none"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#crop"
                data-name={name}
                data-config={JSON.stringify(fileConfig)}
            >
            </button>
        </>
    )
}
