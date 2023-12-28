import {useState, useEffect, useCallback} from 'preact/hooks';
import {memo} from 'preact/compat';
import Comment from "./Comments/Comment";
import CommentsForm from "./Comments/Form";
import {usePaginateFetch} from "../hooks";
import {Comment as Skeleton} from "./Skeletons";
import {useInView} from "react-intersection-observer";
import {jsonFetch} from '../hooks'

const Comments = memo(({target, defaultSort}) => {

    const {items: comments, setItems: setComments, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch(`/api/comments?video_id=${target}&sort=${defaultSort}`)
    const [primaryLoading, setPrimaryLoading] = useState(true)

    const [selectedSort, setSelectedSort] = useState(defaultSort);

    const {ref,inView} = useInView();

    useEffect( async () => {
        load().then(() => setPrimaryLoading(false)).catch(e => e)
    }, []);

    useEffect( async () => {
        if (inView && !loading) {
            load().then(() => setPrimaryLoading(false)).catch(e => e)
        }
    }, [inView]);

    const add = useCallback(async (data) => {
       return jsonFetch(`/api/comments` , {
            method: 'POST',
            body: JSON.stringify({
                ...data,
                video_id: parseInt(target)
            })
        }).then(comment => {
            setComments(comments => [comment, ...comments]);
            setCount(count => count + 1);
            document.getElementById('message-content').value = '';
        }).catch(e => e)
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

    const remove = useCallback(async (comment) => {
        return jsonFetch(`/api/comments/${comment.id}` , {
            method: 'DELETE',
        }).then(() => {
            setComments(comments => comments.filter(c => c.id !== comment.id))
            setCount(count => count - 1);
        }).catch(e => e);
    }, []);

    const pin = useCallback(async (comment, action= 'pin') => {
        return jsonFetch(`/api/comments/${comment.id}/${action}`, {
            method: 'POST'
        }).then( async () => await reload(selectedSort))
            .catch(e => e)
    }, []);

    const sort = async (type) => {
        if (type !== selectedSort) {
            setSelectedSort(type)
            await reload(type)
        }
    }

    const reload = async (type) => {
        setPrimaryLoading(true);
        jsonFetch(`/api/comments?video_id=${target}&sort=${type}`).then(data => {
            setPrimaryLoading(false);
            setComments(data.data)
            setNext(data.links.next)
        }).catch(e => e)
    }

    const activeButton = (type) => selectedSort === type ? 'primary ' : 'outline-primary ';

    return (
        <div className="mb-4">
            <div className="mb-3 d-flex align-items-center justify-content-between">
                {
                    primaryLoading ? <div>Loading ...</div> : <div>{count} Comment{count > 1 && 's'}</div>
                }
                <div className={'d-flex gap-2 align-items-center'}>
                    <button onClick={() => sort('top')} className={'btn btn-' + activeButton('top') + 'btn-sm'}>Top Comments</button>
                    <button onClick={() => sort('newest')} className={'btn btn-' + activeButton('newest') + 'btn-sm'}>Newest first</button>
                </div>
            </div>
            <CommentsForm add={add}/>
            {
                (primaryLoading) ?
                    <div className="d-flex flex-column gap-2">
                        {[...Array(4).keys()].map(i => <Skeleton key={i}/>)}
                    </div>
                : comments.map(comment => <Comment
                        key={comment.id}
                        comment={comment}
                        remove={remove}
                        update={update}
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
})

export default Comments;
