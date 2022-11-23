import { useState, useEffect } from 'preact/hooks';
import {usePaginateFetch} from "../hooks/usePaginateFetch";

export default function Interactions ({target}) {

    const {items: interactions, setItems: setInteractions, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch(`/api/interactions/${target}`)
    const [primaryLoading, setPrimaryLoading] = useState(true)

    const [selectedSort, setSelectedSort] = useState('top');

    useEffect( async () => {
        setPrimaryLoading(true);
        await load()
        setPrimaryLoading(false);
    }, []);

    return (
        <>
            <ul className="nav nav-tabs" role="tablist">
                <li className="nav-item" role="presentation">
                    <button className="nav-link active" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                        All
                    </button>
                </li>
                <li className="nav-item" role="presentation">
                    <button className="nav-link" data-bs-toggle="tab" data-bs-target="#likes" type="button" role="tab"
                            aria-controls="likes" aria-selected="false">
                        Likes&nbsp;
                        <i className="fa-regular fa-thumbs-up"></i>
                    </button>
                </li>
                <li className="nav-item" role="presentation">
                    <button className="nav-link" data-bs-toggle="tab" data-bs-target="#dislikes" type="button" role="tab"
                            aria-controls="dislikes" aria-selected="false">
                        Dislikes&nbsp;
                        <i className="fa-regular fa-thumbs-down"></i>
                    </button>
                </li>
            </ul>
            <div className="tab-content" style="height: 350px;overflow-y: auto;">
                <div className="tab-pane active h-100" id="all" role="tabpanel" aria-labelledby="all-tab">
                    {
                        primaryLoading &&
                            <div className={'d-flex justify-content-center align-items-center h-100'}>
                                <div className="spinner-border" role="status">
                                    <span className="visually-hidden">Loading...</span>
                                </div>
                            </div>
                    }
                    <div className="d-flex flex-column justify-content-center gap-2 mt-3">
                        <ul className="list-group">
                            {
                                interactions.map(interaction => (
                                    <li className="list-group-item d-flex justify-content-between align-items-center">
                                        <a href={interaction.user.route} className="d-flex align-items-center gap-2">
                                            <img className="rounded" src={interaction.user.avatar} alt={interaction.user.username + ' avatar'} style="width: 40px;"/>
                                             <span>{interaction.user.username}</span>
                                        </a>
                                        <subscribe-button isSubscribe={interaction.user.is_subscribe} user={interaction.user.id} size={'sm'}/>
                                    </li>
                                ))}
                        </ul>
                    </div>
                </div>
                <div className="tab-pane" id="likes" role="tabpanel" aria-labelledby="likes-tab">

                </div>
                <div className="tab-pane" id="dislikes" role="tabpanel" aria-labelledby="dislikes-tab">

                </div>
            </div>
        </>
    )
}
