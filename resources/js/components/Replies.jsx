import { useState, useEffect } from 'preact/hooks';
import {usePaginateFetch} from "../hooks";
import Comment from "./Comments/Comment";
import {Comment as Skeleton} from "./Skeletons";
import {useInView} from "react-intersection-observer";
import CommentsForm from "./Comments/Form";

export default function Replies ({target, video}) {

    const {items: comments, setItems: setComments, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch(`/api/comments/${target}/replies`)
    const [primaryLoading, setPrimaryLoading] = useState(true)

    const [selectedSort, setSelectedSort] = useState('top');

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

    const reply = async (data) => {

        const response = await fetch('/api/comments', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'POST',
            credentials: 'include',
            body: JSON.stringify({
                ...data,
                video_id: video,
                parent_id: target
            })
        });

        const comment = await response.json();

        setComments(comments => [comment.data, ...comments]);
        setCount(count => count + 1);

        document.getElementById('content').value = '';
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
    };

    const sort = async (type) => {
        if (type !== selectedSort) {
            setSelectedSort(type)
            setPrimaryLoading(true);
            const response = await fetch(`/api/comments/${target}/replies?sort=${type}`);
            const data = await response.json()
            setPrimaryLoading(false);
            setComments(data.data)
            setNext(data.links.next)
        }
    }

    const activeButton = (type) => selectedSort === type ? 'primary ' : 'outline-primary ';

   return (
        <div className="mb-4">
            <div className="mb-3 d-flex align-items-center justify-content-between">
                <div>{count} Replie{count > 1 && 's'}</div>
                <div className={'d-flex gap-2 align-items-center'}>
                    <button onClick={() => sort('top')} className={'btn btn-' + activeButton('top') + 'btn-sm'}>Top Replies</button>
                    <button onClick={() => sort('recent')} className={'btn btn-' + activeButton('recent') + 'btn-sm'}>Newest replies</button>
                </div>
            </div>
            <CommentsForm auth={true} addComment={reply} placeholder={'Add a reply...'} label={'Write reply'}/>
            {
                (primaryLoading) ?
                    <div className="d-flex flex-column gap-2">
                        {[...Array(4).keys()].map(i => <Skeleton key={i}/>)}
                    </div>
                    : comments.map(comment => <Comment key={comment.id} comment={comment} auth={true} canReply={false} deleteComment={deleteComment} updateComment={updateComment}/>)
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
