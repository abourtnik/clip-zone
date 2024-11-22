import {useEffect,} from "preact/hooks";
import {RefObject} from "preact";

export function useClickOutside (ref: RefObject<HTMLElement>, callback: Function) {
    useEffect(() => {
        const clickCallback = (e: any) => {
            if (ref.current && !ref.current.contains(e.target)) {
                callback()
            }
        }
        document.addEventListener('click', clickCallback)
        return function cleanup () {
            document.removeEventListener('click', clickCallback)
        }
    }, [ref, callback])
}
