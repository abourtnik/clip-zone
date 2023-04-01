import { useState, useRef } from 'preact/hooks';

export default function ImageUpload ({source = null, name, width, height, ratio = null, area = 1.0}) {

    const [hover, setHover] = useState(false);

    const input = useRef(null)
    const button = useRef(null)

    const change = async (event) => {

        const file = event.target.files[0];

        if(file.size > 2097152) {
            document.getElementById('toast-message').innerText = `Your ${name} is too large (${Math.round((file.size / 1000000) * 100) / 100} MB) Its size should not exceed 2 MB.`;
            new bootstrap.Toast(document.getElementById('toast')).show()
            return;
        }

        input.current.value = '';

        const image = new Image();

        image.onload = function() {

            if (this.width < width || this.height < height) {
                document.getElementById('toast-message').innerText = `Your ${name} is too small (${this.width} x ${this.height}). The ${name} image must be at least ${width} x ${height} pixels`;
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
            {
                source ?
                   <div className={"position-relative overflow-hidden" + (name === 'avatar' ? ' rounded-circle border border-secondary' : '')}>
                        <img className="img-fluid" alt={name + ' image'} src={source} />
                        <div
                            className={"position-absolute w-100 text-center text-white start-50 " + (hover ? 'opacity-100 top-50' : 'opacity-0 top-75')}
                            style="z-index: 2;transition: all 0.3s ease-in-out 0s;transform: translate(-50%, -50%);"
                        >
                            <div className="fw-bold">Click to update {name}</div>
                        </div>
                        <input
                            ref={input}
                            style="z-index: 3"
                            className="position-absolute bottom-0 left-0 right-0 top-0 opacity-0 cursor-pointer w-100"
                            type="file"
                            accept="image/*"
                            name={name}
                            onChange={change}
                            onMouseEnter={() => setHover(true)} onMouseLeave={() => setHover(false)}
                        />
                        <div style="transition: all 0.4s ease-in-out 0s;"
                             className={"position-absolute bg-dark bg-opacity-75 bottom-0 left-0 right-0 top-0 w-100 " + (hover ? 'opacity-100' : 'opacity-0') }>
                        </div>
                    </div>
                    :
                    <div className="input-file">
                        <label htmlFor={name} className="rounded">
                            <i className="fas fa-upload"></i>
                            <div className="mt-2">Upload Video Poster</div>
                        </label>
                        <input ref={input} type="file" accept="image/*" name={name} id={name} required onChange={change} />
                    </div>
            }
            <button
                ref={button}
                className="d-none"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#crop"
                data-name={name}
                data-width={width}
                data-height={height}
                data-ratio={ratio}
                data-area={area}
                data-resizeable={name !== 'banner'}
            >
            </button>
        </>
    )
}
