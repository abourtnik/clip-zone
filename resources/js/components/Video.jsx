export default function Video ({video}) {
    return (
        <article className="col-sm-6 col-md-6 col-lg-6 col-xl-4 col-xxl-3">
            <div className="position-relative h-100 card">
                <a href={video.route}>
                    <div className="position-relative">
                        <img className="img-fluid rounded-top w-100" src={video.thumbnail} alt={video.title}/>
                        <small className="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                            {video.duration}
                        </small>
                    </div>
                    <span style="position: absolute;inset: 0;"></span>
                </a>
                <div className="d-flex mt-2 p-2">
                    <a href={video.user.route} style="height: 36px;" className="position-relative" title={video.user.username}>
                        <img className="rounded-circle" src={video.user.avatar} alt={video.user.username + ' avatar'} style={{width: '36px'}} />
                    </a>
                    <div className="ml-2">
                        <a href={video.route} className="fw-bolder text-decoration-none text-black d-block position-relative" title={video.title}>{video.short_title}</a>
                        <a href={video.user.route} className="position-relative text-decoration-none">
                            <small>{video.user.username}</small>
                        </a>
                        <small className="text-muted d-block">{video.views} • {video.publication_date}</small>
                    </div>
                </div>
            </div>
        </article>
    )
}
