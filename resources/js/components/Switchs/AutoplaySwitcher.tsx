import {useEffect, useState} from "preact/hooks";

const STORAGE_KEY = 'autoplay-video';

export function AutoplaySwitcher() {

    const [checked, setChecked] = useState<boolean>(JSON.parse(localStorage.getItem(STORAGE_KEY) || 'true'));

    useEffect(() => {
        localStorage.setItem(STORAGE_KEY, checked.toString());
    }, [checked])

    return (
        <div className="d-flex gap-2 align-items-center justify-content-evenly">
            <span className="text-sm text-black">Videos auto-play</span>
            <div className="input-switch d-inline-block position-relative cursor-pointer">
                <input checked={checked} type="checkbox" id="autoplay-video" aria-label="Activer la lecture automatique" class="autoplay-switcher" onChange={() => setChecked(v => !v)} />
                <label for="autoplay-video">
                    <span className="switch"></span>
                    <svg
                        className="icon icon-on"
                        fill="currentColor"
                        width="800px"
                        height="800px"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path d="M18.54,9,8.88,3.46a3.42,3.42,0,0,0-5.13,3V17.58A3.42,3.42,0,0,0,7.17,21a3.43,3.43,0,0,0,1.71-.46L18.54,15a3.42,3.42,0,0,0,0-5.92Zm-1,4.19L7.88,18.81a1.44,1.44,0,0,1-1.42,0,1.42,1.42,0,0,1-.71-1.23V6.42a1.42,1.42,0,0,1,.71-1.23A1.51,1.51,0,0,1,7.17,5a1.54,1.54,0,0,1,.71.19l9.66,5.58a1.42,1.42,0,0,1,0,2.46Z" />
                    </svg>
                    <svg className="icon icon-off" fill="#000000" width="800px" height="800px" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
                        <path
                            fillRule="evenodd"
                            clipRule="evenodd"
                            d="M6.04995 2.74998C6.04995 2.44623 5.80371 2.19998 5.49995 2.19998C5.19619 2.19998 4.94995 2.44623 4.94995 2.74998V12.25C4.94995 12.5537 5.19619 12.8 5.49995 12.8C5.80371 12.8 6.04995 12.5537 6.04995 12.25V2.74998ZM10.05 2.74998C10.05 2.44623 9.80371 2.19998 9.49995 2.19998C9.19619 2.19998 8.94995 2.44623 8.94995 2.74998V12.25C8.94995 12.5537 9.19619 12.8 9.49995 12.8C9.80371 12.8 10.05 12.5537 10.05 12.25V2.74998Z"
                            fill="#000000"
                        />
                    </svg>
                </label>
            </div>
        </div>
    )
}
