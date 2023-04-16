import { useState, useEffect, useCallback } from 'preact/hooks';
import {usePaginateFetch} from "../hooks";
import Comment from "./Comments/Comment";
import {Comment as Skeleton} from "./Skeletons";
import {useInView} from "react-intersection-observer";
import CommentsForm from "./Comments/Form";
import {jsonFetch} from '../hooks'

export default function Replies ({target, video, auth}) {

    const user = JSON.parse(auth);

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

    const reply = useCallback(async (data) => {
        return jsonFetch(`/api/comments` , {
            method: 'POST',
            body: JSON.stringify({
                ...data,
                video_id: video,
                parent_id: target
            })
        }).then(comment => {
            setComments(comments => [comment, ...comments]);
            setCount(count => count + 1);
            document.getElementById('content').value = '';
        }).catch(e => e)
    }, []);

    const remove = useCallback(async (comment) => {
        return jsonFetch(`/api/comments/${comment.id}` , {
            method: 'DELETE',
        }).then(() => {
            setComments(comments => comments.filter(c => c.id !== comment.id))
            setCount(count => count - 1);
        }).catch(e => e);
    }, []);

    const update = useCallback(async (comment, content) => {
        return jsonFetch(`/api/comments/${comment.id}` , {
            method: 'PUT',
            body: JSON.stringify({
                content: content,
            }),
        }).then(updated_comment => {
            setComments(comments => comments.map(c => c.id === comment.id ? updated_comment : c))
        })
    },[]);

    const sort = async (type) => {
        if (type !== selectedSort) {
            setSelectedSort(type)
            await reload(type)
        }
    }

    const reload = async (type) => {
        setPrimaryLoading(true);
        jsonFetch(`/api/comments/${target}/replies?sort=${type}`).then(data => {
            setPrimaryLoading(false);
            setComments(data.data)
            setNext(data.links.next)
        }).catch(e => e)
    }

    const activeButton = (type) => selectedSort === type ? 'primary ' : 'outline-primary ';

   return (
        <div className="mb-4">
            <div className="mb-3 d-flex align-items-center justify-content-between">
                <div>{count} {count > 1 ? 'Replies' : 'Reply'}</div>
                <div className={'d-flex gap-2 align-items-center'}>
                    <button onClick={() => sort('top')} className={'btn btn-' + activeButton('top') + 'btn-sm'}>Top Replies</button>
                    <button onClick={() => sort('recent')} className={'btn btn-' + activeButton('recent') + 'btn-sm'}>Newest replies</button>
                </div>
            </div>
            <CommentsForm user={user} add={reply} placeholder={'Add a reply...'} label={'Write reply'}/>
            {
                (primaryLoading) ?
                    <div className="d-flex flex-column gap-2">
                        {[...Array(4).keys()].map(i => <Skeleton key={i}/>)}
                    </div>
                    : comments.map(comment => <Comment
                        key={comment.id}
                        comment={comment}
                        user={user}
                        canReply={false}
                        remove={remove}
                        update={update}
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
