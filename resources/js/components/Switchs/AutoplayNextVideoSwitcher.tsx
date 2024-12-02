import {useEffect, useState} from "preact/hooks";
import {AUTOPLAY_NEXT_VIDEO_KEY} from "./keys";
export function AutoplayNextVideoSwitcher() {

    const [checked, setChecked] = useState<boolean>(JSON.parse(localStorage.getItem(AUTOPLAY_NEXT_VIDEO_KEY) || 'false'));

    useEffect(() => {
        localStorage.setItem(AUTOPLAY_NEXT_VIDEO_KEY, checked.toString());
    }, [checked])

    return (
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="autoplay" checked={checked} onChange={() => setChecked(v => !v)} />
            <label class="form-check-label" for="autoplay">Autoplay</label>
        </div>
    )
}
