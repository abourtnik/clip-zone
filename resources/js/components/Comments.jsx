import { useState, useEffect } from 'preact/hooks';
import Comment from "./Comment";
import {usePaginateFetch} from "../hooks/usePaginateFetch";

export default function Comments ({target, auth}) {

    const {items: comments, setItems: setComments, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch(`/api/comments/${target}`)
    const [primaryLoading, setPrimaryLoading] = useState(true)

    const [selectedSort, setSelectedSort] = useState('top');

    useEffect( async () => {
        setPrimaryLoading(true);
        await load()
        setPrimaryLoading(false);
    }, []);

    const addComment = async (event) => {

        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        const response = await fetch(`/api/comments`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'POST',
            credentials: 'include',
            body: JSON.stringify({
                ...data,
                target: parseInt(target)
            })
        });

        const comment = await response.json();

        setComments(comments => [comment.data, ...comments]);
        setCount(count => count + 1);

        document.getElementById('content').value = '';
    }

    const sort = async (type) => {
        setSelectedSort(type)
        setPrimaryLoading(true);
        const response = await fetch(`/api/comments/${target}?sort=${type}`);
        const data = await response.json()
        setPrimaryLoading(false);
        setComments(data.data)
        setNext(data.links.next)
    }

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

    const updateComment = async (comment, content) => {

        const response = await fetch(`/api/comments/${comment.id}`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'PUT',
            body: JSON.stringify({
                content: content,
            }),
            credentials: 'include'
        });

        const updated_comment = await response.json();

        setComments(comments => comments.map(c => c.id === comment.id ? updated_comment.data : c))
    }

    const activeButton = (type) => selectedSort === type ? 'primary ' : 'outline-primary ';

    return (
        <div className="mb-4">
            <div className="mb-3 d-flex align-items-cente justify-content-between">
                <div>{count} Commentaires</div>
                <div className={'d-flex gap-2 align-items-center'}>
                    <button onClick={() => sort('top')} className={'btn btn-' + activeButton('top') + 'btn-sm'}>Top Comments</button>
                    <button onClick={() => sort('recent')} className={'btn btn-' + activeButton('recent') + 'btn-sm'}>Most recent first</button>
                </div>
            </div>
            {
                auth ?
                <form onSubmit={addComment}>
                    <div className="mb-2">
                        <label htmlFor="content" className="form-label d-none"></label>
                        <textarea className="form-control" id="content" rows="1" name="content" placeholder="Ajouter un commentaire ..." required></textarea>
                    </div>
                    <div className="mb-3 d-flex justify-content-end">
                        <button type="submit" className="btn btn-success btn-sm">
                            Write commment
                        </button>
                    </div>
                </form> :

                <div className={'alert alert-primary d-flex justify-content-between align-items-center'}>
                    <span>Connectez-vous pour ecrire un commentaire</span>
                    <a href={'/login'} className={'btn btn-primary btn-sm'}>
                        Se connecter
                    </a>
                </div>
            }
            {
                (primaryLoading) ?
                    <div className={'text-center mt-3'}>
                        <div className="spinner-border" role="status">
                            <span className="visually-hidden">Loading...</span>
                        </div>
                    </div>
                : comments.map(comment => <Comment key={comment.id} comment={comment} auth={auth} canReply={true} deleteComment={deleteComment} updateComment={updateComment}/>)
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
