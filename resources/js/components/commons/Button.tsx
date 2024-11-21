import clsx from "clsx";
import { ComponentChildren } from "preact";

type Props = {
    loading?: boolean,
    color?: string,
    small?: boolean,
    children: ComponentChildren;
} & JSX.IntrinsicElements['button'];

export function Button ({loading= false, children, color = 'primary', small = true, ...props} : Props) {
    return (
        <button className={clsx('btn btn-' + color, small && 'btn-sm')} disabled={loading} {...props}>
            {loading && <span className="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>}
            {children}
        </button>
    )
}
