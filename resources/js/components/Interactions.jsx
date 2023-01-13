import { useState, useEffect } from 'preact/hooks';
import {usePaginateFetch} from "../hooks";
import {ThumbsDownRegular, ThumbsUpRegular } from "./Icon";

export default function Interactions ({target}) {

    const {items: interactions, setItems: setInteractions, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch(`/api/interactions?video_id=${target}`)
    const [primaryLoading, setPrimaryLoading] = useState(true)

    const [filter, setFilter] = useState('all');
    const [search, setSearch] = useState('');

    const activeButton = (type) => filter === type ? 'primary ' : 'outline-primary ';

    useEffect( async () => {
        setPrimaryLoading(true);
        await load()
        setPrimaryLoading(false);
    }, []);

    const filtering = async (type) => {
        setFilter(type)
        setInteractions([]);
        setPrimaryLoading(true);
        const response = await fetch(`/api/interactions?video_id=${target}&filter=${type}&search=${search}`);
        const data = await response.json()
        setPrimaryLoading(false);
        setInteractions(data.data)
        //setCount(data.)
    }

    const searching = async event => {
        const value = event.target.value;
        setSearch(value)
        setInteractions([]);
        setPrimaryLoading(true);
        const response = await fetch(`/api/interactions?video_id=${target}&filter=${filter}&search=${value}`);
        const data = await response.json()
        setPrimaryLoading(false);
        setInteractions(data.data)
    }

    return (
        <div style="height: 450px;">
            <div className={'d-flex gap-2 align-items-center'}>
                <button onClick={() => filtering('all')} className={'btn btn-' + activeButton('all') + 'btn-sm'}>All</button>
                <button onClick={() => filtering('up')} className={'d-flex align-items-center gap-1 btn btn-' + activeButton('up') + 'btn-sm'}>
                    <span>Only</span>
                    <ThumbsUpRegular/>
                </button>
                <button onClick={() => filtering('down')} className={'d-flex align-items-center gap-1 btn btn-' + activeButton('down') + 'btn-sm'}>
                    Only
                    <ThumbsDownRegular/>
                </button>
                <input onChange={searching} type="text" className={'form-control form-control-sm'} placeholder="Search user" aria-label="Search"/>
            </div>
            {
                primaryLoading &&
                    <div className={'d-flex justify-content-center align-items-center h-100'}>
                        <div className="spinner-border" role="status">
                            <span className="visually-hidden">Loading...</span>
                        </div>
                    </div>
            }
            <div className="my-3" style="overflow-y: auto;height:400px;">
                {
                    interactions.length ?
                        <>
                            <small className={'text-muted mb-1 d-block'}>{count} Results</small>
                            <ul className="list-group">
                                {
                                    interactions.map(interaction => (
                                        <li className="list-group-item d-flex justify-content-between align-items-center">
                                            <div className={'d-flex align-items-center gap-4'}>
                                                <div className={'badge bg-' + (interaction.status ? 'success' : 'danger')}>
                                                    {interaction.status ? <ThumbsUpRegular/> : <ThumbsDownRegular/>}
                                                </div>
                                                <a href={interaction.user.route} className="d-flex align-items-center gap-2">
                                                    <img className="rounded" src={interaction.user.avatar} alt={interaction.user.username + ' avatar'} style="width: 40px;"/>
                                                    <span>{interaction.user.username}</span>
                                                </a>
                                            </div>
                                            <subscribe-button isSubscribe={interaction.user.is_subscribe} user={interaction.user.id} size={'sm'}/>
                                        </li>
                                    ))
                                }
                            </ul>
                        </>

                        :
                        (!primaryLoading) &&
                            <div className={'d-flex justify-content-center align-items-center h-100 w-50 m-auto'}>
                                <div className={'card card-body text-center fw-bold'}>No interactions found</div>
                            </div>
                }
            </div>
        </div>
    )
}
