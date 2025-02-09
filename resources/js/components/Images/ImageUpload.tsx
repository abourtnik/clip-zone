import { useRef } from 'preact/hooks';
import configuration from "@/config";
import {show as showToast} from "@/functions/toast";
import {ChangeEvent} from "react";

const MB = 1048576;

type Props = {
    name: string,
    config: 'avatar' | 'banner'| 'thumbnail',
}

export default function ImageUpload ({name, config, ...attributes} : Props) {

    const input = useRef<HTMLInputElement>(null);
    const button = useRef<HTMLButtonElement>(null)

    const fileConfig = configuration[config];

    const change = async (event: ChangeEvent<HTMLInputElement>) => {

        const file = event.currentTarget.files?.[0];

        event.currentTarget.value = '';

        if(!file) {
            return;
        }

        if(!configuration.accepted_format.includes(file.type)) {
            showToast(`The file type is invalid (${file.type}). Allowed types are ${configuration.accepted_format.join(', ')}`)
            return;
        }

        if(file.size > (fileConfig.maxSize * MB)) {
            showToast(`Your ${name} file is too large (${Math.round((file.size / 1000000) * 100) / 100} MB) The uploading file should not exceed ${fileConfig.maxSize} MB.`)
            return;
        }

        const image = new Image();

        image.onload = function() {

            if (image.width < fileConfig.minWidth || image.height < fileConfig.minHeight) {
                showToast(`Your ${name} file is too small (${image.width} x ${image.height}). The ${name} image must be at least ${fileConfig.minWidth} x ${fileConfig.minHeight} pixels`)
            } else {
                document.getElementById('cropped_image')!.setAttribute('src', URL.createObjectURL(file));
                button.current!.click()
            }
        };

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
