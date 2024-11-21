export function CommentSkeleton() {
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
