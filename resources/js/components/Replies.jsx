import { useState, useEffect } from 'preact/hooks';
import {usePaginateFetch} from "../hooks/usePaginateFetch";
import Like from "./Like";

export default function Replies ({target}) {

    const {items: comments, setItems: setComments, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch(`/api/comments/${target}/replies`)
    const [primaryLoading, setPrimaryLoading] = useState(true)

    const [selectedSort, setSelectedSort] = useState('top');

    useEffect( async () => {
        await load()
        setPrimaryLoading(false);
    }, []);

    const deleteComment = async (comment) => {

        const response = await fetch(`/api/comments/${comment.id}`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'DELETE',
            credentials: 'include'
        });

        setComments(comments => comments.filter(c => c.id !== comment.id))
        setCount(count => count - 1);
    }

    const sort = async (type) => {
        setSelectedSort(type)
        setPrimaryLoading(true);
        const response = await fetch(`/api/comments/${target}/replies?sort=${type}`);
        const data = await response.json()
        setPrimaryLoading(false);
        setComments(data.data)
        setNext(data.links.next)
    }

    const activeButton = (type) => selectedSort === type ? 'primary ' : 'outline-primary ';

   return (
        <div className="mb-4">
            <div className="mb-3 d-flex align-items-center justify-content-between">
                <div>{count} Comment{count > 1 && 's'}</div>
                <div className={'d-flex gap-2 align-items-center'}>
                    <button onClick={() => sort('top')} className={'btn btn-' + activeButton('top') + 'btn-sm'}>Top Replies</button>
                    <button onClick={() => sort('recent')} className={'btn btn-' + activeButton('recent') + 'btn-sm'}>Newest replies</button>
                </div>
            </div>
            <form onSubmit={null}>
                <div className="mb-2">
                    <label htmlFor="content" className="form-label d-none"></label>
                    <textarea className="form-control" id="content" rows="1" name="content" placeholder="Add a reply..." required></textarea>
                </div>
                <div className="mb-3 d-flex justify-content-end">
                    <button type="submit" className="btn btn-success btn-sm">
                        Write reply
                    </button>
                </div>
            </form>
            {
                (primaryLoading) ?
                    <div className={'text-center mt-3'}>
                        <div className="spinner-border" role="status">
                            <span className="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    : comments.map(comment => (
                        <div className="d-flex mb-3 gap-2">
                            <a href={comment.user.route}>
                                <img className="rounded-circle img-fluid" src={comment.user.avatar} alt={comment.user.username + ' avatar'} style="width: 50px;"/>
                            </a>
                            <div className={'w-100'}>
                                <div className={'border p-3 bg-white'}>
                                    <div className="d-flex justify-content-between align-items-center mb-1">
                                        <div className={'d-flex align-items-center gap-2'}>
                                            <a href={comment.user.route}>
                                                {comment.user.username}
                                            </a>•
                                            <small className="text-muted">{comment.created_at}</small>
                                        </div>
                                        <div className={'d-flex align-items-center gap-2'}>
                                            <button onClick={() => deleteComment(comment)} className={"btn btn-sm text-danger p-0"}>Delete</button>
                                            {comment.is_updated && <small className="text-muted fw-semibold">Modified</small>}
                                        </div>
                                    </div>
                                    <div className={'my-3'}>{comment.content}</div>
                                    <div className="d-flex align-items-center gap-2 mt-1">
                                        <div className="d-flex align-items-center gap-2">
                                            <Like
                                                active={JSON.stringify({'like': comment.liked_by_auth_user, 'dislike': comment.disliked_by_auth_user})}
                                                model={comment.model}
                                                target={comment.id}
                                                count={JSON.stringify({'likes_count' : comment.likes_count, 'dislikes_count' : comment.dislikes_count})}
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))
            }
            {
                (!primaryLoading && hasMore) &&
                <button type={'button'} onClick={() => load(selectedSort)} className={'btn btn-outline-primary w-100'}>
                    {
                        loading ?
                            <div>
                                <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span className="sr-only">Loading...</span>
                            </div> :
                            <span>En charger plus</span>
                    }
                </button>
            }

        </div>
    )
}
