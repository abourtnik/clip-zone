import {useState} from "react";
import {KeyboardEvent} from "preact";

type Options = {
    length: number | undefined,
    onSelect: (index: number) => void
}
export function useKeyboardNavigate (options: Options) {

    const {length, onSelect} = options;

    const [index, setIndex] = useState<number | null>(null);
    const navigate = (e: KeyboardEvent<HTMLInputElement>) => {

        if (['ArrowDown', 'ArrowUp', 'Enter'].includes(e.key)) {

            if (length === undefined || length === 0) {
                setIndex(null);
                return
            }

            switch (e.key) {
                case 'ArrowDown':
                    setIndex(i => {
                        if(i === null) return 0
                        else if (length - 1 === i) return null
                        else return  i + 1
                    })
                    break;
                case 'ArrowUp':
                    setIndex(i => {
                        if(i === null) return length - 1
                        else if (i === 0) return null
                        else return  i - 1
                    })
                    break;
                case 'Enter':
                    if (index !== null && index < length) {
                        e.preventDefault();
                        onSelect(index);
                    }
                    break;
            }
        }
    }

    return {index, navigate}
}
