import { useState, useEffect, useContext } from 'preact/hooks';
import { createContext } from 'preact';
import { clsx } from 'clsx';
import ImageUpload from "./ImageUpload";
import config from "../config";

// Must be synchronized with app/Enums/ThumbnailStatus.php
const STATUS = {
    "PENDING" : 0,
    "GENERATED" : 1,
    "ERROR" : 2,
    "UPLOADED" : 3,
}

const SelectedContext = createContext({
    selected : {},
    setSelected: () => {}
})

function SelectedContextProvider ({thumbnails, children}) {
    const initialState = () => {

        const defaultSelected = thumbnails.find(t => t.is_active);

        return {
            id: defaultSelected?.id ?? null,
            url: defaultSelected?.url ?? null,
        }
    }

    const [selected, setSelected] = useState(initialState);

    useEffect(() => {
        if (selected?.url) {
            document.querySelector('video')?.setAttribute('poster', selected.url)
        }
    }, [selected])

    return <SelectedContext.Provider value={{
        selected,
        setSelected
    }}>
        {children}
    </SelectedContext.Provider>

}

function useSelected (id) {

    const {selected, setSelected} = useContext(SelectedContext);

    return {
        selected,
        setSelected,
        active: id === selected.id
    }
}

export default function Thumbnails ({data}) {

    const parsed = JSON.parse(data);

    const upload  = parsed.find(t => t.status === STATUS.UPLOADED);

    const thumbnails = parsed.filter(t => t.status !== STATUS.UPLOADED)

    return (
        <SelectedContextProvider thumbnails={parsed}>
            <div className="row gx-3 gy-3">
                <Upload initial={upload}/>
                {thumbnails.map(thumbnail => <Thumbnail thumbnail={thumbnail}/>)}
            </div>
            <Input/>
        </SelectedContextProvider>
    )
}

function Thumbnail({thumbnail}) {

    const {setSelected, active} = useSelected(thumbnail.id);

    const [hover, setHover] = useState(false);

    const [status, setStatus] = useState(thumbnail.status);

    useEffect(() => {
        window.PRIVATE_CHANNEL.listen('.thumbnail.generated', (data) => {
            if (data.id === thumbnail.id){
                setStatus(data.status)
                if (data.is_active) {
                    setSelected({id: thumbnail.id, url: thumbnail.url})
                }
            }
        }, []);

        return () => {
            window.PRIVATE_CHANNEL.stopListening('.thumbnail.generated')
        }
    }, []);

    const render = () => {
        if (status === STATUS.PENDING) {
            return (
                <div className={'placeholder-wave h-100 w-100 position-relative video-thumbnail'}>
                    <span className="placeholder h-100 w-100"></span>
                    <span className="position-absolute top-50 start-50 translate-middle w-100 text-center text-white">Auto-generating ...</span>
                </div>
            )
        }

        if (status === STATUS.GENERATED) {
            return (
                <img
                    className={clsx('img-fluid cursor-pointer opacity-50', hover && 'opacity-100', active && 'border-4 border-primary opacity-100')}
                    src={thumbnail.url}
                    alt="Thumbnail"
                    onMouseEnter={() => setHover(true)}
                    onMouseLeave={() => setHover(false)}
                    onClick={() => setSelected({id: thumbnail.id, url: thumbnail.url})}
                />

            )
        }

        if (status === STATUS.ERROR) {
            return (
                <div
                    className="w-100 h-100 bg-secondary text-white d-flex justify-content-center align-items-center w-100 h-100">
                    <i className="fa-solid fa-circle-exclamation fa-2x"></i>
                </div>
            )
        }
    }

    return (
        <div className={'col-6'}>
            {render()}
        </div>
    )


}

function Upload({initial}) {

    const {setSelected, active} = useSelected(initial?.id ?? 0);

    const [upload, setUpload] = useState(initial?.url);

    const [hover, setHover] = useState(false);

    useEffect(() => {
        window.addEventListener('cropped-thumbnail_file', e => {

            const file = e.detail;
            const url = URL.createObjectURL(file);
            setUpload(url);
            setSelected({
                id: initial?.id ?? 0,
                url: url,
            })
        })

        return () => window.removeEventListener('cropped', null)
    }, [])

    const select = () => {
        setSelected({
            id : initial?.id ?? 0,
            url: upload
        })
    }

    return (
        <div class={'col-6'}>
            {
                !upload ?
                    <div class={'h-100'}>
                        <div id={'thumbnail-rules'} className={'d-none'}>
                            <ul>
                                <li>Make your thumbnail 1280 by 720 pixels (16:9 ratio)</li>
                                <li>Min size required is {config.thumbnail.minWidth} x {config.thumbnail.minHeight}</li>
                                <li>Ensure that your thumbnail is less than {config.thumbnail.maxSize}MB</li>
                                <li>Use a file PNG, JPEG, JPG, WEBP format</li>
                            </ul>
                        </div>
                        <div className={'input-file position-relative h-100'}>
                            <button
                                className={'position-absolute p-0 bg-transparent'}
                                type="button"
                                data-bs-toggle="popover"
                                data-bs-placement="top"
                                data-bs-title="Recommendations"
                                data-bs-content-id="thumbnail-rules"
                                data-bs-trigger="focus"
                                tabIndex="0"
                                style={{top: '10px', right: '10px'}}
                            >
                                <i className="fa-solid fa-circle-info"></i>
                            </button>
                            <label htmlFor="thumbnail_file" className={'rounded h-100 p-0 w-100 d-flex align-items-center justify-content-center'}>
                                <div>
                                    <i className="fas fa-image"></i>
                                    <div className="mt-2 fw-">Upload Thumbnail</div>
                                </div>
                            </label>
                            <ImageUpload name={'thumbnail_file'} config={'thumbnail'} readonly/>
                        </div>
                    </div>
                    :
                    <div
                        class={'position-relative cursor-pointer w-100'}
                        onMouseEnter={() => setHover(true)}
                        onMouseLeave={() => setHover(false)}
                    >
                        <div className={clsx('position-absolute top-5 right-5 dropdown', !hover && 'd-none')}>
                            <button className="bg-dark bg-opacity-75 dropdown-toggle without-caret rounded-circle w-10 p-2 d-flex align-items-center justify-content-center dot"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    onClick={(e) => e.stopPropagation()}
                            >
                                <i className="fa-solid fa-ellipsis-vertical text-white fw-bold"></i>
                            </button>
                            <ul className="dropdown-menu">
                                <li>
                                    <button className="dropdown-item btn btn-file" type={'button'}>
                                        <ImageUpload name={'thumbnail_file'} config={'thumbnail'} readonly/>
                                        <div class={'text-sm d-flex align-items-center gap-2 cursor-pointer'}>
                                            <i className="fas fa-image"></i>
                                            <span>Change</span>
                                        </div>
                                    </button>
                                </li>
                                <li>
                                    <a download className="dropdown-item d-flex align-items-center gap-2 text-sm" href={upload}>
                                        <i className="fa-solid fa-download"></i>
                                        <span>Download</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <img
                            className={clsx('img-fluid opacity-50', hover && 'opacity-100', active && 'border-4 border-primary opacity-100')}
                            onClick={select}
                            src={upload}
                            alt="Thumbnail"
                            id="upload-image"
                        />
                    </div>
            }
        </div>
    )

}

function Input () {

    const {selected} = useSelected(null);

    return (
        <>
            <input type="hidden" name="thumbnail" value={selected.id}/>
            <input type="file" name='thumbnail_file' className={'d-none'}/>
        </>

    )
}


