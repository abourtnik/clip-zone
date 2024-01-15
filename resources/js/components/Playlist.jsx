import { useState, useCallback, useRef } from 'preact/hooks';
import {debounce} from "../functions";
import { ReactSortable } from "react-sortablejs";
import {jsonFetch, useClickOutside} from '../hooks'

export default function Playlist ({initial = []}) {

    const ref = useRef(null)

    const [search, setSearch] = useState('');
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [videos, setVideos] = useState(initial.length ? JSON.parse(initial) : initial);
    const [showSuggestions, setShowSuggestions] = useState(false);

    useClickOutside(ref, () => setShowSuggestions(false))

    const handleChange = e => {
        const value = e.target.value;
        setSearch(value);
        setLoading(true);
        suggest(value)
    };

    const suggest = useCallback(debounce(async value => {

        setShowSuggestions(true);

        jsonFetch(`/api/search/videos?q=${value}`, {
            method: 'POST',
            body : JSON.stringify({
                'except_ids' : videos.map(v => v.id)
            })
        }).then(data => {
            setData(data.data);
        }).catch(e => e).finally(() =>  setLoading(false));
    }, 300), [videos]);

    const addVideo = (video) => {
        setShowSuggestions(false);
        setVideos(videos => [...videos, video])
        setData(data => data.filter(v => v.id !== video.id))
    }

    const remove = (video) => {
        setVideos(videos => videos.filter(v => v.id !== video.id))
        setData(data => [...data, video])
    }

    return (
        <div className="card shadow-soft h-100">
            <div className="card-body">
                <h5 className="text-primary">Videos { videos.length > 0 && '• ' + videos.length}</h5>
                <hr/>
                <div className={'position-relative'}>
                    <div ref={ref} className="d-flex w-100 position-relative">
                        <input
                            onChange={handleChange}
                            className="form-control rounded-5"
                            type="search"
                            placeholder="Search Videos"
                            aria-label="Search"
                            name="q"
                            value={search}
                            onClick={() => setShowSuggestions(true)}
                        />
                        {
                            (loading && search.trim()) &&
                            <div className="position-absolute top-50 right-0 translate-middle" >
                                <div className={'spinner-border spinner-border-sm'} role="status">
                                    <span className="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        }
                    </div>
                    {
                        (showSuggestions && search.trim() && !loading) &&
                        <div className={'position-absolute shadow-lg  left-0 w-100 bg-white shadow-lg border border-1 pt-3 overflow-auto'} style={{top:'39px',zIndex:3, maxHeight: '557px'}}>
                            {
                                data.length ?
                                    <ul className={'list-group list-group-flush mb-0'}>
                                        {
                                            data.map(result => (
                                                <li style={{cursor: 'pointer'}} className={'list-group-item p-0'} onClick={() => addVideo(result)}>
                                                    <div className="d-flex align-items-start gap-2 justify-content-start px-3 py-2 hover-primary">
                                                        <div className={'position-relative'}>
                                                            <img style={{width:'150px', height: '84px'}} src={result.thumbnail} alt=""/>
                                                            <small className="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                                                                {result.duration}
                                                            </small>
                                                        </div>
                                                        <div>
                                                            <div className={'text-black fw-bold text-sm mb-1'}>
                                                                {result.title}
                                                            </div>
                                                            <div className={'text-muted text-sm'}>
                                                                {result.user.username}
                                                            </div>
                                                            <div className={'text-muted text-sm'}>
                                                                {result.views} • {result.publication_date}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            ))
                                        }
                                    </ul>
                                    :
                                    <div className={'d-flex justify-content-center align-items-center flex-column pb-3'}>
                                        <strong>No results found</strong>
                                    </div>
                            }
                        </div>
                    }
                </div>
                <hr/>
                <div style={{height: '557px'}} className={'overflow-auto'}>
                    {
                        videos.length ?
                            <ReactSortable
                                tag="ul"
                                list={videos}
                                setList={setVideos}
                                className={'list-group list-group-flush mb-0 overflow-auto'}
                                ghostClass='bg-light-dark'
                                handle={'.handle'}
                                animation={150}
                            >
                                {
                                    videos.map(video => (
                                        <li className={'list-group-item p-0'}>
                                            <div className="d-flex align-items-center justify-content-between gap-3 px-3 py-2">
                                                <div className={'d-flex align-items-center gap-3 d-flex  w-100'}>
                                                    <div className={'handle cursor-move'}>
                                                        <i className="fa-solid fa-bars"></i>
                                                    </div>
                                                    <div className={'d-flex flex-column flex-sm-row gap-2 w-100 align-items-center'}>
                                                        {
                                                            video.is_private ?
                                                                <>
                                                                    <div className={'col-12 col-sm-6 col-lg-5 col-xl-4'}>
                                                                        <div
                                                                            className="bg-secondary text-white d-flex justify-content-center align-items-center w-100"
                                                                            style="height: 100px">
                                                                            <i className="fa-solid fa-lock fa-2x"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div className={'px-0 px-sm-3 col-12 col-sm-6 col-lg-7 col-xl-8'}>
                                                                        <div className='alert alert-secondary mb-0'>
                                                                            <div className={'text-sm fw-bold text-center'}>This video is private</div>
                                                                            <div className={'text-sm'}>The author update video status to private</div>
                                                                        </div>
                                                                    </div>
                                                                </>
                                                                :
                                                                <>
                                                                    <div className={'position-relative col-12 col-sm-6 col-lg-5 col-xl-4'}>
                                                                        <img className={'img-fluid'} src={video.thumbnail} alt=""/>
                                                                        <small className="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                                                                            {video.duration}
                                                                        </small>
                                                                    </div>
                                                                    <div className={'px-3 col-12 col-sm-6 col-lg-7 col-xl-8'}>
                                                                        <div className={'text-black fw-bold text-sm mb-1'}>
                                                                            {video.title}
                                                                        </div>
                                                                        <div className={'text-muted text-sm'}>
                                                                            {video.user.username}
                                                                        </div>
                                                                        <div className={'text-muted text-sm'}>
                                                                            {video.views} • {video.publication_date}
                                                                        </div>
                                                                    </div>
                                                                </>
                                                        }
                                                    </div>
                                                </div>
                                                <button
                                                    className="bg-transparent py-1 btn-sm"
                                                    type="button"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Remove"
                                                    onClick={() => remove(video)}
                                                >
                                                    <i className="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name={'videos[]'} value={video.id}/>
                                        </li>
                                    ))
                                }
                            </ReactSortable> :
                            <div className={'d-flex flex-column gap-1 justify-content-center align-items-center h-100 w-100'}>
                                <div className={'fw-bold fs-5'}>No videos added</div>
                                <p className={'text-muted text-sm text-center'}>Add videos to your playlist by selecting your videos or videos from other channels</p>
                            </div>
                    }
                </div>
            </div>
        </div>
    )
}
