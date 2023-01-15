export function Video ({}) {
    return (
        <div className="col-md-6 col-lg-4 col-xl-3 mb-4 gap-4">
            <div className="bg-light-dark rounded w-100" style="height: 200px"></div>
            <div className="placeholder-glow d-flex align-items-center gap-2 w-100 mt-2">
                <span className="placeholder rounded-circle" style="width: 36px; height:36px"></span>
                <div className={'d-flex flex-column w-100 gap-2'}>
                    <span className="placeholder bg-secondary w-75"></span>
                    <span className="placeholder bg-secondary w-50"></span>
                </div>
            </div>
        </div>
    )
}

export function Comment ({}) {
    return (
        <div className={'placeholder-glow d-flex align-items-start gap-3 w-100 mb-3'}>
            <span className="placeholder rounded-circle" style="width: 36px; height:36px"></span>
            <div className={'w-100 d-flex flex-column gap-2'}>
                <div className={'placeholder bg-secondary w-25'}></div>
                <div className={'placeholder bg-secondary w-100'} style="height:100px"></div>
            </div>
        </div>
    )
}

export function Interaction ({}) {
    return (
        <div className={'placeholder-glow d-flex align-items-center gap-2 w-100 mb-1'}>
            <span className="placeholder rounded-circle" style="width: 36px; height:36px"></span>
            <div className={'placeholder bg-secondary w-100'} style="height:40px"></div>
        </div>
    )
}

