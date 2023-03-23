import { useState, useCallback, useRef } from 'preact/hooks';
import {debounce} from "../functions";
import {Cross, Bars} from './Icon'
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

        jsonFetch(`/api/search-videos?q=${value}`, {
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
    }

    const remove = (video) => {
        setVideos(videos => videos.filter(v => v.id !== video.id))
    }

    return (
        <div className="card shadow-soft">
            <div className="card-body">
                <h5 className="text-primary">Videos { videos.length > 0 && '• ' + videos.length}</h5>
                <hr/>
                <div className={'position-relative'}>
                    <form ref={ref} method="GET" className="d-flex w-100 position-relative" role="search" action="/search">
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
                    </form>
                    {
                        (showSuggestions && search.trim() && !loading) &&
                        <div className={'position-absolute shadow-lg  left-0 w-100 bg-white shadow-lg border border-1 pt-3 overflow-auto'} style={{top:'39px',zIndex:3, maxHeight: '557px'}}>
                            {
                                data.length ?
                                    <ul className={'list-group list-group-flush mb-0'}>
                                        {
                                            data.map(result => (
                                                <li style={{cursor: 'pointer'}} className={'list-group-item p-0'} onClick={() => addVideo(result)}>
                                                    <div className="d-flex align-items-start gap-2 justify-content-start px-3 py-2 result">
                                                        <div className={'position-relative'}>
                                                            <img style={{width:'150px', height: '84px'}} src={result.thumbnail} alt=""/>
                                                            <small className="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                                                                {result.duration}
                                                            </small>
                                                        </div>
                                                        <div>
                                                            <div className={'text-black text-lowercase text-sm'}>{result.title}</div>
                                                            <div className={'text-muted text-lowercase text-sm'}>{result.user.username} • {result.views}</div>
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
                                            <div className="d-flex align-items-center gap-3 justify-content-between px-3 py-2">
                                                <div className={'handle'} style={{cursor: 'move'}}>
                                                    <Bars/>
                                                </div>
                                                <div className={'d-flex w-100 align-items-start gap-2'}>
                                                    <div className={'position-relative'}>
                                                        <img style={{width:'150px', height: '84px'}} src={video.thumbnail} alt=""/>
                                                        <small className="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                                                            {video.duration}
                                                        </small>
                                                    </div>
                                                    <div>
                                                        <div className={'text-black text-lowercase text-sm'}>{video.title}</div>
                                                        <div className={'text-muted text-lowercase text-sm'}>{video.user.username} • {video.views}</div>
                                                    </div>
                                                </div>
                                                <button
                                                    className="bg-transparent py-1 btn-sm"
                                                    type="button"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Remove"
                                                    onClick={() => remove(video)}
                                                >
                                                    <Cross/>
                                                </button>
                                            </div>
                                            <input type="hidden" name={'videos[]'} value={video.id}/>
                                        </li>
                                    ))
                                }
                            </ReactSortable> :
                            <div className={'d-flex flex-column gap-1 justify-content-center align-items-center h-100 w-100'}>
                                <div className={'fw-bold fs-5'}>Add new video</div>
                                <p className={'text-muted text-sm'}>Some description</p>
                            </div>
                    }
                </div>
            </div>
        </div>
    )
}
