import { useState, useRef } from 'preact/hooks';
import Resumable from 'resumablejs'
import {formatSizeUnits} from "@/functions/size";
import { useTranslation } from "react-i18next";
import {ChangeEvent} from "react";

type Props = {
    endpoint: string,
    maxsize: number
}

interface CustomConfigurationHash extends Resumable.ConfigurationHash {
    permanentErrors?: number[];
}

export function VideoUpload ({endpoint, maxsize} : Props) {

    const { t } = useTranslation();

    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<string>('');
    const [progress, setProgress] = useState<number>(0);
    const [isDrag, setDrag] = useState<boolean>(false);

    const input = useRef<HTMLInputElement>(null)

    const change = async (event: ChangeEvent<HTMLInputElement>) => {

        setError('');

        const file = event.currentTarget.files?.[0];

        event.currentTarget.value = '';

        if(!file) {
            return;
        }

        if(file.size > maxsize) {
            setError(`Your ${name} file is too large (${formatSizeUnits(file.size)}) The uploading file should not exceed ${formatSizeUnits(maxsize)}.`)
            return;
        }

        setLoading(true);

        const uniqueId = Date.now();

        const token = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement;

        const resumable = new Resumable({
            target: endpoint,
            query:{
                _token: token.getAttribute('content')
            },
            chunkSize: 10 * 1000 * 1000, // 10MB
            forceChunkSize: true,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token.getAttribute('content')
            },
            method: 'multipart',
            simultaneousUploads: 1,
            testChunks: false,
            permanentErrors: [400, 401, 403, 404, 409, 413, 415, 419, 422, 500, 501],
            setChunkTypeFromFile : true,
            generateUniqueIdentifier: () => uniqueId.toString(),
        } as CustomConfigurationHash);

        window.resumable = resumable;

        resumable.addFile(file)

        resumable.on('fileAdded', function (file) {
            resumable.upload()
        });

        resumable.on('fileProgress', function (file) {
            setProgress(Math.round(file.progress(false) * 100));
        });

        resumable.on('fileSuccess', function (file: Resumable.ResumableFile, response :string) {
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
                                <div
                                    className="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     aria-valuenow={progress}
                                     aria-valuemin={0}
                                     aria-valuemax={100}
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
