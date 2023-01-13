export default function Skeleton ({}) {
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
