import {useEffect, useState} from "preact/hooks";
import {AUTOPLAY_NEXT_VIDEO_KEY} from "./keys";
import {useTranslation} from "react-i18next";
export function AutoplayNextVideoSwitcher() {

    const { t } = useTranslation();

    const [checked, setChecked] = useState<boolean>(JSON.parse(localStorage.getItem(AUTOPLAY_NEXT_VIDEO_KEY) || 'false'));

    useEffect(() => {
        localStorage.setItem(AUTOPLAY_NEXT_VIDEO_KEY, checked.toString());
    }, [checked])

    return (
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="autoplay" checked={checked} onChange={() => setChecked(v => !v)} />
            <label class="form-check-label" for="autoplay">{t('Autoplay')}</label>
        </div>
    )
}
