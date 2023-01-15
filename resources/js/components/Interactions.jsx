import { useState, useEffect } from 'preact/hooks';
import {usePaginateFetch} from "../hooks";
import {ThumbsDownRegular, ThumbsUpRegular } from "./Icon";
import {useInView} from "react-intersection-observer";
import {Interaction as Skeleton} from "./Skeletons";

export default function Interactions ({target}) {

    const {items: interactions, setItems: setInteractions, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch(`/api/interactions?video_id=${target}`)
    const [primaryLoading, setPrimaryLoading] = useState(true)

    const [filter, setFilter] = useState('all');
    const [search, setSearch] = useState('');

    const {ref,inView} = useInView();

    const activeButton = (type) => filter === type ? 'primary ' : 'outline-primary ';

    useEffect( async () => {
        await load()
        setPrimaryLoading(false);
    }, []);

    useEffect( async () => {
        if (inView && !loading) {
            await load()
        }
    }, [inView]);

    const filtering = async (type) => {
        setFilter(type)
        setInteractions([]);
        setPrimaryLoading(true);
        const response = await fetch(`/api/interactions?video_id=${target}&filter=${type}&search=${search}`);
        const data = await response.json()
        setPrimaryLoading(false);
        setInteractions(data.data)
        setCount(data.meta.total)
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
        setCount(data.meta.total)
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
            <hr/>
            <small className={'text-muted mb-1 d-block'}>{ primaryLoading ? 'Loading ...' : count + ' Results' }</small>
            <hr/>
            {
                primaryLoading ?
                <div className="d-flex flex-column gap-2 my-3">
                    {[...Array(6).keys()].map(i => <Skeleton key={i}/>)}
                </div>
                :
                <div className="my-3" style="overflow-y: auto;height:330px;">
                        {
                            interactions.length ?
                                <>
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
                                        {
                                            hasMore &&
                                            <div ref={ref} className="d-flex flex-column gap-2 my-3">
                                                {[...Array(6).keys()].map(i => <Skeleton key={i}/>)}
                                            </div>
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
            }

        </div>
    )
}
