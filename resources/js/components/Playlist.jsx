import { useState, useCallback } from 'preact/hooks';
import {debounce} from "../functions";
import {Cross} from './Icon'

export default function Playlist ({}) {

    const [search, setSearch] = useState('');
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [videos, setVideos] = useState([]);
    const [showSuggestions, setShowSuggestions] = useState(false);

    const handleChange = e => {
        const value = e.target.value;
        setSearch(value);
        suggest(value)
    };

    const suggest = useCallback(debounce(async value => {

        setShowSuggestions(true);

        const response = await fetch('/api/search-videos?q=' + value, {
            headers: {
                'Accept': 'application/json'
            },
        });

        const data = await response.json();

        setData(data.data);
        setLoading(false)

    }, 300), []);

    const addVideo = (video) => {
        setShowSuggestions(false);
        setVideos(videos => [video, ...videos])
    }

    const remove = (video) => {
        setVideos(videos => videos.filter(v => v.id !== video.id))
    }

    return (
        <div>
            <div className={'position-relative'}>
                <form method="GET" className="d-flex w-100" role="search" action="/search">
                    <div className="input-group">
                        <input onChange={handleChange} className="form-control rounded-5" type="search" placeholder="Search Videos" aria-label="Search" name="q" value={search}/>
                    </div>
                </form>
                {
                    (search.trim() && !loading) &&
                    <div className={'position-absolute shadow-lg  left-0 w-100 bg-white shadow-lg border border-1 pt-3 overflow-auto ' + (showSuggestions ? '' : ' d-none')} style={{top:'39px',zIndex:3, maxHeight: '557px'}}>
                        {
                            data.length ?
                                <ul className={'list-group list-group-flush mb-0'}>
                                    {
                                        data.map(result => (
                                            <li style={{cursor: 'pointer'}} className={'list-group-item p-0'} onClick={() => addVideo(result)}>
                                                <div className="d-flex align-items-start gap-2 justify-content-start px-3 py-2 result">
                                                    <img style={{width:'150px', height: '84px'}} src={result.thumbnail} alt=""/>
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
            <div style={{height: '557px'}}>
                {
                    videos.length ?
                        <ul className={'list-group list-group-flush mb-0 overflow-auto'} >
                            {
                                videos.map(video => (
                                    <li className={'list-group-item p-0'}>
                                        <div className="d-flex align-items-start gap-2 justify-content px-3 py-2">
                                            <img style={{width:'150px', height: '84px'}} src={video.thumbnail} alt=""/>
                                            <div className={'d-flex w-100 align-items-center justify-content-between'}>
                                                <div>
                                                    <div className={'text-black text-lowercase text-sm'}>{video.title}</div>
                                                    <div className={'text-muted text-lowercase text-sm'}>{video.user.username} • {video.views}</div>
                                                </div>
                                                <button
                                                    className="bg-danger bg-opacity-50 rounded-4 py-1 btn-sm"
                                                    type="button"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Remove"
                                                    onClick={() => remove(video)}
                                                >
                                                    <Cross/>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name={'videos[]'} value={video.id}/>
                                    </li>
                                ))
                            }
                        </ul> :
                        <div className={'d-flex flex-column gap-1 justify-content-center align-items-center h-100 w-100'}>
                            <div className={'fw-bold fs-5'}>No video selected</div>
                            <p className={'text-muted text-sm'}>Some description</p>
                        </div>
                }
            </div>
        </div>
    )
}
