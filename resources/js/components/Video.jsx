export default function Video ({video}) {
    return (
        <article className="col-md-6 col-lg-4 col-xl-3">
            <div className="position-relative">
                <a href={video.route}>
                    <div className="position-relative">
                        <img className="img-fluid w-100 rounded-4" src={video.thumbnail} alt={video.title} style="width: 360px; height: 202px;object-fit: cover;"/>
                        <small className="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                            {video.duration}
                        </small>
                    </div>
                    <span style="position: absolute;inset: 0;"></span>
                </a>
                <div className="d-flex mt-2">
                    <a href={video.user.route} style="height: 36px;" className="position-relative">
                        <img className="rounded-circle" src={video.user.avatar} alt={video.user.username + ' avatar'} style={{width: '36px'}} />
                    </a>
                    <div className="ml-2">
                        <div className="fw-bolder">{video.title}</div>
                        <a href={video.user.route} className="position-relative text-decoration-none text-black">
                            <small>{video.user.username}</small>
                        </a>
                        <small className="text-muted d-block">{video.views} • {video.created_at}</small>
                    </div>
                </div>
            </div>
        </article>
    )
}
