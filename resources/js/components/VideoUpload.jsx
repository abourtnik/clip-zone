import { useState, useRef } from 'preact/hooks';
import configuration from "../config";
import Resumable from 'resumablejs'
import {formatSizeUnits} from "../functions";
import { useTranslation } from "react-i18next";

const MB = 1048576;

export default function VideoUpload ({endpoint, maxsize}) {

    const { t } = useTranslation();

    const config = configuration['video'];

    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [progress, setProgress] = useState(0);
    const [isDrag, setDrag] = useState(false);

    const input = useRef(null)

    const change = async (event) => {

        setError('');

        const file = event.target.files[0];

        input.current.value = '';

        if(!file) {
            return;
        }

        if(!config.accepted_format.includes(file.type)) {
            setError(`The file type is invalid (${file.type}). Allowed types are ${config.accepted_format.join(', ')}`)
            return;
        }

        if(file.size > maxsize) {
            setError(`Your ${name} file is too large (${formatSizeUnits(file.size)}) The uploading file should not exceed ${formatSizeUnits(maxsize)}.`)
            return;
        }

        setLoading(true);

        const uniqueId = Date.now();

        const resumable = new Resumable({
            target: endpoint,
            query:{
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            chunkSize: 10 * 1000 * 1000, // 10MB
            forceChunkSize: true,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            method: 'multipart',
            simultaneousUploads: 1,
            testChunks: false,
            permanentErrors: [400, 401, 403, 404, 409, 413, 415, 422, 500, 501],
            setChunkTypeFromFile : file.type,
            generateUniqueIdentifier: () => uniqueId,
        });

        window.resumable = resumable;

        resumable.addFile(file)

        resumable.on('fileAdded', function (file) {
            resumable.upload()
        });

        resumable.on('fileProgress', function (file) {
            setProgress(Math.round(file.progress() * 100));
        });

        resumable.on('fileSuccess', function (file, response) {
            setLoading(false)
            const data = JSON.parse(response);
            window.location.replace(data.route);
        });

        resumable.on('fileError', function (file, response) {
            setLoading(false)
            const data = JSON.parse(response);
            setError(data?.message ?? 'Whoops! An error occurred. Please try again later.')
        });
    }

    const cancel = () => {
        window.resumable.cancel();
        setLoading(false);
    }

    return (
        <>
            {
                (!loading || error) &&
                <div className="block w-full position-relative bg-white appearance-none rounded-md hover:shadow-outline-gray p-3">
                    <label className="d-none" htmlFor="file"></label>
                    <input
                        id="file"
                        type="file"
                        name="file"
                        className="position-absolute top-0 bottom-0 start-0 end-0 w-100 h-100 outline-none opacity-0 cursor-pointer"
                        ref={input}
                        onChange={change}
                        onDragEnter={() => setDrag(true)}
                        onDragLeave={() => setDrag(false)}
                        onDrop={() => setDrag(false)}
                        accept="video/*"

                    />
                    <div style={'border-style: dashed !important;'} className={"text-center align-items-center d-flex flex-column w-100 mx-auto py-3" + (isDrag ? ' bg-light border border-2' : '') }>
                        <img src="/images/upload.jpg" className={'mb-4'} alt=""/>
                        <strong className="mb-1">{t('Drag and drop video file to upload')}</strong>
                        <div className="text-muted">Your video will be private until you publish them.</div>
                        <button className="btn btn-primary mt-4 text-uppercase">
                            Select file
                        </button>
                    </div>
                </div>
            }
            {
                error &&
                <div className={"alert w-90 mx-auto alert-danger mb-3 text-center"}>
                    <strong className={'text-sm'}>{error}</strong>
                </div>
            }
            {
                (loading && !error) &&
                <div className="text-center align-items-center d-flex flex-column w-75 mx-auto p-3">
                    <img src="/images/uploading.gif" className={'mb-4'} alt="" style={{width: 350, height: 350}}/>
                    <h2 className="mb-1">Your video is uploading ... </h2>
                    <div className="text-primary fw-bold">Dont quit or reload page please</div>
                    {
                        loading &&
                        <div className={'w-100'}>
                            <div className="progress w-100 mt-4 border">
                                <div className="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     aria-valuenow={progress}
                                     aria-valuemin="0"
                                     aria-valuemax="100"
                                     style={{width: progress + '%'}}
                                >
                                </div>
                            </div>
                            <div className={'mt-1 fw-bold'}>{progress + '%'}</div>
                        </div>
                    }
                    <button className="btn btn-secondary mt-4 text-uppercase" onClick={cancel}>
                        Cancel Upload
                    </button>
                </div>
            }
        </>
    )
}
