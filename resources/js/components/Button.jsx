export default function Button ({loading= false, children, color = 'primary', ...props}) {
    return (
        <button className={"btn btn-" + color + " btn-sm"} disabled={loading} {...props}>
            {loading && <span className="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>}
            {children}
        </button>
    )
}
