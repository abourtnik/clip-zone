/**
 * Debounce un callback
 */
export function debounce (callback, delay) {
    let timer = null;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            callback.apply(this, args)
        }, delay)
    }

}
