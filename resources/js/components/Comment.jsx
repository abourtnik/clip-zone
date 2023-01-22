import {useRef, useState, useEffect, useCallback} from 'preact/hooks';
import { memo } from 'preact/compat';
import {useToggle} from "../hooks";

import Interaction from "./Interaction";
import {ThumbsDownSolid, ThumbsUpSolid} from "./Icon";

const Comment = memo(({comment, auth, canReply, deleteComment, updateComment}) => {

    const [onEdit, setOnEdit] = useState(false);
    const [showReply, setShowReply] = useState(false);
    const [replies, setReplies] = useState(comment.replies);
    const [showReplies, setShowReplies] = useState(false);
    const [expand, setExpand] = useToggle(false);

    useEffect(() => {
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    }, [])

    const reply = async (event) => {

        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        const response = await fetch('/api/comments', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'POST',
            credentials: 'include',
            body: JSON.stringify({
                ...data,
                video_id: parseInt(comment.video.id),
                parent_id: comment.id
            })
        });

        const new_reply = await response.json();

        setReplies(replies => [new_reply.data, ...replies]);
        setShowReplies(true);
        setShowReply(false);

        document.getElementById('content-' + comment.id).value = '';
    }

    const deleteReply = useCallback (async (reply) => {

        const response = await fetch(`/api/comments/${reply.id}`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'DELETE',
            credentials: 'include'
        });

        setReplies(replies => replies.filter(r => r.id !== reply.id))
    }, []);

    const updateReply = useCallback (async (reply, content) => {

        const response = await fetch(`/api/comments/${reply.id}`, {
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

        const updated_reply = await response.json();

        setReplies(replies => replies.map(r => r.id === reply.id ? updated_reply.data : r))
    }, []);

    let attributes =  {
        ...(!auth && {
            'data-bs-toggle': "popover",
            'data-bs-placement': "left",
            'data-bs-title': "Want to reply to this comment ?",
            'data-bs-trigger': "focus",
            'data-bs-html': "true",
            'data-bs-content': "Sign in for reply.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>",
        })
    }

    const update = async (comment) => {
        await updateComment(comment, textarea.current.value)
        setOnEdit(false);
    }


    const showRepliesText = (show, length) => {
        const count = length > 1 ? replies.length + ' replies' : 'reply';
        return (show ? 'Hide ' : 'Show ') + count;
    }

    const textarea = useRef(null)

    return (
        <article className="d-flex mb-3 gap-2">
            <a href={comment.user.route} className={'h-100'}>
                <img className="rounded-circle img-fluid" src={comment.user.avatar} alt={comment.user.username + ' avatar'} style="width: 50px;"/>
            </a>
            <div className={'w-100'}>
                <div className={'border p-3 bg-white'}>
                    <div className="d-flex justify-content-between align-items-center">
                        <div className={'d-flex align-items-center gap-2'}>
                            <a href={comment.user.route} className={'text-decoration-none'}>
                                {comment.user.username}
                            </a>•
                            <small className="text-muted">{comment.created_at}</small>
                        </div>
                        <div className={'d-flex align-items-center gap-2'}>
                            {comment.can_update && <button onClick={() => setOnEdit(onEdit => !onEdit)} className={"btn btn-sm text-primary p-0"}>Update</button>}
                            {comment.can_delete && <button onClick={() => deleteComment(comment)} className={"btn btn-sm text-danger p-0"}>Delete</button>}
                            {comment.is_updated && <small className="text-muted fw-semibold">Modified</small>}
                        </div>
                        {

                        }

                    </div>
                    {
                        onEdit ?
                            <div className={'my-3'}>
                                <textarea className="form-control" rows="3" name="content" ref={textarea} required>{comment.content}</textarea>
                                <div className={'d-flex gap-1 mt-2'}>
                                    <button onClick={() => update(comment)} className={'btn btn-primary btn-sm'}>Update</button>
                                    <button onClick={() => setOnEdit(false)} className={'btn btn-secondary btn-sm'}>Cancel</button>
                                </div>
                            </div>
                            : <div className={comment.is_long ? 'my-2' : 'mt-2 mb-3'} style={{whiteSpace: 'pre-line'}}>
                                <div className={'text-sm'}>{expand ? comment.content : comment.short_content}</div>
                                {comment.is_long && <button onClick={setExpand} className={'text-primary bg-transparent ps-0 text-sm mt-1'}>{expand ? 'Show less': 'Read more'}</button>}
                            </div>
                    }
                    <div className="d-flex align-items-center gap-2 mt-1">
                        <div className="d-flex align-items-center gap-2">
                            {
                                auth ?
                                    <Interaction
                                        active={JSON.stringify({'like': comment.liked_by_auth_user, 'dislike': comment.disliked_by_auth_user})}
                                        model={comment.model}
                                        target={comment.id}
                                        count={JSON.stringify({'likes_count' : comment.likes_count, 'dislikes_count' : comment.dislikes_count})}
                                        auth={auth}
                                    />
                                    :
                                    <div className="d-flex justify-content-between gap-1">
                                        <button
                                            className="btn btn-sm btn-outline-success d-flex gap-1 align-items-center"
                                            data-bs-toggle="popover"
                                            data-bs-placement="left"
                                            data-bs-title="Like this comment ?"
                                            data-bs-trigger="focus"
                                            data-bs-html="true"
                                            data-bs-content="Sign in to make your opinion count.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                                        >
                                            <ThumbsUpSolid/>
                                            <span>{comment.likes_count}</span>
                                        </button>
                                        <button
                                            className="btn btn-sm btn-outline-danger d-flex gap-1 align-items-center"
                                            data-bs-toggle="popover"
                                            data-bs-placement="right"
                                            data-bs-title="Don't like this comment ?"
                                            data-bs-trigger="focus"
                                            data-bs-html="true"
                                            data-bs-content="Sign in to make your opinion count.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                                        >
                                            <ThumbsDownSolid/>
                                            <span>{comment.dislikes_count}</span>
                                        </button>
                                    </div>
                            }

                        </div>
                        {
                            canReply &&  <button
                                className="btn btn-sm text-info"
                                {...attributes}
                                onClick={() => auth && setShowReply(true)}
                            >
                                Reply
                            </button>
                        }

                    </div>
                    {
                        showReply &&
                        <form className={'mt-2'} onSubmit={reply}>
                            <div className="mb-2">
                                <label htmlFor={"content-" + comment.id} className="form-label d-none"></label>
                                <textarea className="form-control" id={"content-" + comment.id} rows="1" name="content" placeholder="Add a reply ..." required maxLength={5000}></textarea>
                            </div>
                            <div className="d-flex justify-content-end gap-1">
                                <button
                                    onClick={() => setShowReply(false)}
                                    className="btn btn-sm btn-secondary"
                                >
                                    Cancel
                                </button>
                                <button type="submit" className="btn btn-primary btn-sm">
                                    Reply
                                </button>
                            </div>
                        </form>
                    }
                    {
                        replies.length > 0 &&
                        <button className={'btn btn-sm text-primary my-1 mt-2 ps-0 fw-bold'} onClick={() => setShowReplies(showReplies => !showReplies)}>
                            {showRepliesText(showReplies, replies.length)}
                        </button>
                    }
                </div>
                {
                    showReplies &&
                    <div className={'mt-3'}>
                        {
                            replies.map(comment =>
                                <Comment
                                    key={comment.id}
                                    comment={comment}
                                    auth={auth}
                                    canReply={false}
                                    deleteComment={deleteReply}
                                    updateComment={updateReply}
                                />
                            )
                        }
                    </div>
                }
            </div>
        </article>
    )
});

export default Comment;
