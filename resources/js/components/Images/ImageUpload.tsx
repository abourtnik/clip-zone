import { useRef } from 'preact/hooks';
import configuration from "@/config";
import {show as showToast} from "@/functions/toast";
import {ChangeEvent, InputHTMLAttributes} from "react";
import {useTranslation} from "react-i18next";

const MB = 1048576;

type Props = {
    name: string,
    config: 'avatar' | 'banner'| 'thumbnail',
} & InputHTMLAttributes<HTMLInputElement>

export default function ImageUpload ({name, config, ...attributes} : Props) {

    const input = useRef<HTMLInputElement>(null);
    const button = useRef<HTMLButtonElement>(null);

    const { t } = useTranslation();

    const fileConfig = configuration[config];

    const change = async (event: ChangeEvent<HTMLInputElement>) => {

        const file = event.currentTarget.files?.[0];

        event.currentTarget.value = '';

        if(!file) {
            return;
        }

        if(!configuration.accepted_format.includes(file.type)) {
            showToast(t('Your file type is invalid ({type}). Allowed types are allowedTypes', {type :file.type, allowedTypes: configuration.accepted_format.join(', ')}))
            return;
        }

        if(file.size > (fileConfig.maxSize * MB)) {
            showToast(
                t('Your file is too large ({size} MB) The uploading file should not exceed {maxSize} MB', {
                    size: Math.round((file.size / 1000000) * 100) / 100,
                    maxSize: fileConfig.maxSize
                })
            )
            return;
        }

        const image = new Image();

        image.onload = function() {

            if (image.width < fileConfig.minWidth || image.height < fileConfig.minHeight) {
                showToast(
                    t('Your file is too small ({width} x {height}). The uploading image must be at least {minWidth} x {minHeight} pixels', {
                        width: image.width,
                        height: image.height,
                        minWidth: fileConfig.minWidth,
                        minHeight: fileConfig.minHeight
                    })
                )
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
