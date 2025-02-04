import {useState, KeyboardEvent} from "react";

type Options = {
    length: number | undefined,
    onSelect: (index: number) => void,
    onDefaultSelect: () => void
}
export function useKeyboardNavigate (options: Options) {

    const {length, onSelect, onDefaultSelect} = options;

    const [index, setIndex] = useState<number | null>(null);

    const resetIndex = () => setIndex(null);

    const navigate = (e: KeyboardEvent<HTMLInputElement>) => {

        if (['ArrowDown', 'ArrowUp', 'Enter'].includes(e.key)) {

            e.preventDefault();

            if (length === undefined || length === 0) {
                setIndex(null);
                return
            }

            switch (e.key) {
                case 'ArrowDown':
                    setIndex(i => {
                        if (i === null) return 0
                        else if (length - 1 === i) return null
                        else return i + 1
                    })
                    break;
                case 'ArrowUp':
                    setIndex(i => {
                        if (i === null) return length - 1
                        else if (i === 0) return null
                        else return i - 1
                    })
                    break;
                case 'Enter':

                    if (index === null) {
                        onDefaultSelect();
                    } else {
                        e.preventDefault();
                        onSelect(index);
                    }
                    break;
            }
        }
    }

    return {index, navigate, resetIndex}
}
