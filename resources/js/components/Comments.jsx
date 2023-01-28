import {useState, useEffect, useCallback} from 'preact/hooks';
import Comment from "./Comment";
import {usePaginateFetch} from "../hooks";
import {Comment as Skeleton} from "./Skeletons";
import Button from './Button'
import {useInView} from "react-intersection-observer";

export default function Comments ({target, auth, defaultSort}) {

    const {items: comments, setItems: setComments, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch(`/api/comments?video_id=${target}&sort=${defaultSort}`)
    const [primaryLoading, setPrimaryLoading] = useState(true)

    const [selectedSort, setSelectedSort] = useState(defaultSort);

    const {ref,inView} = useInView();

    useEffect( async () => {
        await load()
        setPrimaryLoading(false);
    }, []);

    useEffect( async () => {
        if (inView && !loading) {
            await load()
        }
    }, [inView]);

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
                video_id: parseInt(target)
            })
        });

        const comment = await response.json();

        setComments(comments => [comment.data, ...comments]);
        setCount(count => count + 1);

        document.getElementById('content').value = '';
    }

    const sort = async (type) => {
        if (type !== selectedSort) {
            setSelectedSort(type)
            await reload(type)
        }
    }

    const reload = async (type) => {
        setPrimaryLoading(true);
        const response = await fetch(`/api/comments?video_id=${target}&sort=${type}`);
        const data = await response.json()
        setPrimaryLoading(false);
        setComments(data.data)
        setNext(data.links.next)
    }

    const deleteComment = useCallback(async (comment) => {

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
    }, []);

    const updateComment = useCallback(async (comment, content) => {

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
    },[]);

    const pin = useCallback(async (comment, action= 'pin') => {

        const response = await fetch(`/api/comments/${comment.id}/${action}`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'POST',
            credentials: 'include',
        });

        await reload(selectedSort)

    }, []);

    const activeButton = (type) => selectedSort === type ? 'primary ' : 'outline-primary ';

    return (
        <div className="mb-4">
            <div className="mb-3 d-flex align-items-center justify-content-between">
                <div>{count} Comment{count > 1 && 's'}</div>
                <div className={'d-flex gap-2 align-items-center'}>
                    <button onClick={() => sort('top')} className={'btn btn-' + activeButton('top') + 'btn-sm'}>Top Comments</button>
                    <button onClick={() => sort('newest')} className={'btn btn-' + activeButton('newest') + 'btn-sm'}>Newest first</button>
                </div>
            </div>
            {
                auth ?
                <form onSubmit={addComment}>
                    <div className="mb-2">
                        <label htmlFor="content" className="form-label d-none"></label>
                        <textarea className="form-control" id="content" rows="4" name="content" placeholder="Add a comment..." required maxLength={5000}></textarea>
                    </div>
                    <div className="mb-3 d-flex justify-content-end">
                        <Button type={'submit'}>
                            Commment
                        </Button>
                    </div>
                </form> :

                <div className={'alert alert-primary d-flex justify-content-between align-items-center'}>
                    <span>Sign in to write a comment</span>
                    <a href={'/login'} className={'btn btn-primary btn-sm'}>
                        Sign in
                    </a>
                </div>
            }
            {
                (primaryLoading) ?
                    <div className="d-flex flex-column gap-2">
                        {[...Array(4).keys()].map(i => <Skeleton key={i}/>)}
                    </div>
                : comments.map(comment => <Comment
                        key={comment.id}
                        comment={comment}
                        auth={auth}
                        canReply={true}
                        deleteComment={deleteComment}
                        updateComment={updateComment}
                        pin={pin}
                    />)
            }
            {
                hasMore &&
                <div ref={ref} className="d-flex flex-column gap-2">
                    {[...Array(4).keys()].map(i => <Skeleton key={i}/>)}
                </div>
            }
        </div>
    )
}
