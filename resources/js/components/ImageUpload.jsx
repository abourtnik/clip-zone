import { useRef } from 'preact/hooks';
import configuration from "../config";

const MB = 1048576;

export default function ImageUpload ({name, config, ...attributes}) {

    const input = useRef(null);
    const button = useRef(null)

    const fileConfig = configuration[config];

    const change = async (event) => {

        const file = event.target.files[0];

        input.current.value = '';

        if(!configuration.accepted_format.includes(file.type)) {
            document.getElementById('toast-message').innerText = `The file type is invalid (${file.type}). Allowed types are ${configuration.accepted_format.join(', ')}`;
            new bootstrap.Toast(document.getElementById('toast')).show()
            return;
        }

        if(file.size > (fileConfig.maxSize * MB)) {
            document.getElementById('toast-message').innerText = `Your ${name} file is too large (${Math.round((file.size / 1000000) * 100) / 100} MB) The uploading file should not exceed ${fileConfig.maxSize} MB.`;
            new bootstrap.Toast(document.getElementById('toast')).show()
            return;
        }

        const image = new Image();

        image.onload = function() {

            if (this.width < fileConfig.minWidth || this.height < fileConfig.minHeight) {
                document.getElementById('toast-message').innerText = `Your ${name} file is too small (${this.width} x ${this.height}). The ${name} image must be at least ${fileConfig.minWidth} x ${fileConfig.minHeight} pixels`;
                new bootstrap.Toast(document.getElementById('toast')).show()
            } else {
                document.getElementById('cropped_image').setAttribute('src', URL.createObjectURL(file));
                button.current.click()
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
