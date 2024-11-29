import {useEffect, useState} from "preact/hooks";

const STORAGE_KEY = 'theme';

type Theme = 'light' | 'dark';

type Props = {
    id: string
}

export function ThemeSwitcher({id} : Props) {

    const [theme, setTheme] = useState<Theme>(localStorage.getItem(STORAGE_KEY) as Theme || 'light');

    useEffect(() => {
        localStorage.setItem(STORAGE_KEY, theme);
        window.dispatchEvent(new CustomEvent('theme-changed', {
            detail: {
                theme: theme,
                id: id
            }
        }));
        document.documentElement.setAttribute('data-bs-theme', theme)
    }, [theme])

    useEffect(() => {
        const event =  (e) => {
            if (e.detail.id !== id) {
                setTheme(e.detail.theme)
            }
        }

        window.addEventListener("theme-changed", event)
        return () => window.removeEventListener("theme-changed", event)
    }, [])

    return (
        <div class="input-switch d-inline-block position-relative cursor-pointer">
                <input
                    type="checkbox"
                    id={'_' + id}
                    class="theme-switcher"
                    aria-label="Changer de thème"
                    onChange={(e : any) => setTheme(e.currentTarget.checked ? 'dark' : 'light')}
                    checked={theme === 'dark'}
                />
                <label for={'_' + id}>
                    <span class="switch"></span>
                    <svg class="icon icon-on" fill="currentColor" width="800px" height="800px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/>
                    </svg>
                    <svg class="icon icon-off" fill="#000000" width="800px" height="800px" viewBox="0 0 24 24" id="sun" data-name="Line Color" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12,3V4M5.64,5.64l.7.7M3,12H4m1.64,6.36.7-.7M12,21V20m6.36-1.64-.7-.7M21,12H20M18.36,5.64l-.7.7" style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
                        <circle cx="12" cy="12" r="4" style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></circle>
                    </svg>
                </label>
        </div>
    )
}
