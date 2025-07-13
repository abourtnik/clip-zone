type Props = {
    refetch: Function
    error: Error
}

export function ApiError ({refetch, error}: Props) {
    return (
        <div className={'d-flex justify-content-center align-items-center h-100'}>
            <div className="border border-1 bg-light text-center p-3 w-100">
                <i className="fa-solid fa-triangle-exclamation fa-3x"></i>
                <h3 className="h5 my-3 fw-normal">Something went wrong !</h3>
                <p className="text-muted">{error.message ?? 'Unknown error'}</p>
                <button className="btn btn-primary rounded-5 text-uppercase btn-sm" onClick={() => refetch()}>
                    Try again
                </button>
            </div>
        </div>
    )
}
