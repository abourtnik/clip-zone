import { useState, useEffect, useCallback } from 'preact/hooks';
import {jsonFetch, usePaginateFetch} from "../hooks";
import {ThumbsDownRegular, ThumbsUpRegular } from "./Icon";
import {useInView} from "react-intersection-observer";
import {Interaction as Skeleton} from "./Skeletons";
import Subscribe from "./Subscribe";
import {debounce} from "../functions";

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
        if (type !== filter) {
            setFilter(type)
            setInteractions([]);
            setPrimaryLoading(true);
            jsonFetch(`/api/interactions?video_id=${target}&filter=${type}&search=${search}`).then(data => {
                setPrimaryLoading(false);
                setInteractions(data.data)
                setCount(data.meta.total)
            }).catch(e => e)
        }
    }

    const handleChange = e => {
        const value = e.target.value;
        setSearch(value);
        searching(value)
    };

    const searching = useCallback(debounce(async value => {
        setInteractions([]);
        setPrimaryLoading(true);
        jsonFetch(`/api/interactions?video_id=${target}&filter=${filter}&search=${value}`).then(data => {
            setPrimaryLoading(false);
            setInteractions(data.data)
            setCount(data.meta.total)
        }).catch(e => e)
    }, 300), []);

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
                <input onChange={handleChange} type="text" className={'form-control form-control-sm'} placeholder="Search user" aria-label="Search"/>
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
                                                <li className="list-group-item d-flex flex-wrap justify-content-between align-items-center">
                                                    <div className={'d-flex align-items-center gap-2 gap-sm-4'}>
                                                        <div className={'badge bg-' + (interaction.status ? 'success' : 'danger')}>
                                                            {interaction.status ? <ThumbsUpRegular/> : <ThumbsDownRegular/>}
                                                        </div>
                                                        <div className={'d-flex align-items-center gap-2 ap-sm-4'}>
                                                            <a href={interaction.user.route} className="d-flex align-items-center gap-2 text-decoration-none">
                                                                <img className="rounded" src={interaction.user.avatar} alt={interaction.user.username + ' avatar'} style="width: 40px;"/>
                                                            </a>
                                                            <div className={'d-flex flex-column'}>
                                                                <span className={'text-sm'}>{interaction.user.username}</span>
                                                                <div className={'text-muted text-sm'}>{interaction.perform_at}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <Subscribe isSubscribe={interaction.user.is_subscribe} user={interaction.user.id} size={'sm'}/>
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
