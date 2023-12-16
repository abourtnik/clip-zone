import {useState, useEffect, useCallback} from 'preact/hooks';
import { memo } from 'preact/compat';

import Interaction from "../Interaction";
import ReplyForm from "./ReplyForm";
import Edit from './Edit'

import ConfirmDelete from './ConfirmDelete'
import {jsonFetch} from "../../hooks";

const Comment = memo(({comment, user, canReply, remove, update, pin}) => {

    const [onEdit, setOnEdit] = useState(false);
    const [showReply, setShowReply] = useState(false);
    const [replies, setReplies] = useState(comment.replies);
    const [showReplies, setShowReplies] = useState(false);

    useEffect(() => {
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    }, [])


    const reply = useCallback(async (data) => {
        return jsonFetch(`/api/comments` , {
            method: 'POST',
            body: JSON.stringify({
                ...data,
                video_id: parseInt(comment.video.id),
                parent_id: comment.id
            })
        }).then(comment => {
            setReplies(replies => [comment, ...replies]);
            setShowReplies(true);
            setShowReply(false);
            document.getElementById('content-' + comment.id).value = '';
        }).catch(e => e)
    }, []);

    const deleteReply = useCallback (async (reply) => {
        return jsonFetch(`/api/comments/${reply.id}` , {
            method: 'DELETE',
        }).then(() => {
            setReplies(replies => replies.filter(r => r.id !== reply.id))
        }).catch(e => e);
    }, []);

    const updateReply = useCallback (async (reply, content) => {
        return jsonFetch(`/api/comments/${reply.id}` , {
            method: 'PUT',
            body: JSON.stringify({
                content: content,
            }),
        }).then(updated_reply => {
            setReplies(replies => replies.map(r => r.id === reply.id ? updated_reply : r))
        }).catch(e => e);
    }, []);

    let attributes =  {
        ...(!user && {
            'data-bs-toggle': "popover",
            'data-bs-placement': "right",
            'data-bs-title': "Want to reply to this comment ?",
            'data-bs-trigger': "focus",
            'data-bs-html': "true",
            'data-bs-content': "Sign in for reply.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>",
        })
    }

    const showRepliesText = (show, length) => {
        const count = length > 1 ? replies.length + ' replies' : 'reply';
        return (show ? 'Hide ' : 'Show ') + count;
    }

    return (
        <article className="d-flex mb-3 gap-2">
            <a href={comment.user.route} className={'h-100'}>
                <img className="rounded-circle img-fluid" src={comment.user.avatar} alt={comment.user.username + ' avatar'} style="width: 50px;"/>
            </a>
            <div className={'w-100'}>
                <div className={'border p-3 bg-white'}>
                    {
                        comment.is_pinned &&
                        <div className={'d-flex align-items-center gap-2 mb-3 badge rounded-pill text-bg-primary'} style='width:fit-content'>
                            <i className="fa-solid fa-thumbtack"></i>
                            <span className={'text-white'}>Pinned by {comment.author.username} </span>
                        </div>
                    }
                    <div className="d-flex justify-content-between align-items-center">
                        <div className={'d-flex flex-wrap align-items-center gap-1'}>
                            <a href={comment.user.route} className={'text-decoration-none text-sm' + (comment.user.is_author ? ' badge rounded-pill text-bg-secondary' : '') }>
                                {comment.user.username}
                            </a>
                            <span>•</span>
                            <small className="text-muted">{comment.created_at}</small>
                            {comment.is_updated && <><span>•</span> <small className="text-muted fw-semibold">Modified</small></>}
                        </div>
                        {
                            user &&
                            <div className={'dropdown'}>
                                <button className={'bg-transparent btn-sm dropdown-toggle without-caret'} type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                    <i className="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul className="dropdown-menu">
                                    {
                                        comment.can_pin &&
                                        <li>
                                            <button onClick={() => pin(comment, comment.is_pinned ? 'unpin' : 'pin')} className="dropdown-item d-flex align-items-center gap-3" >
                                                <i className="fa-solid fa-thumbtack"></i>
                                                {comment.is_pinned ? 'Unpin' : 'Pin'}
                                            </button>
                                        </li>
                                    }
                                    {
                                        comment.can_update &&
                                        <li>
                                            <button className="dropdown-item d-flex align-items-center gap-3" onClick={() => setOnEdit(onEdit => !onEdit)}>
                                                <i className="fa-solid fa-pen"></i>
                                                Edit
                                            </button>
                                        </li>
                                    }
                                    {
                                        comment.can_delete && <ConfirmDelete comment={comment} onDelete={remove}/>
                                    }
                                    <li>
                                        {
                                            comment.can_report ?
                                                <button
                                                    className="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#report"
                                                    data-id={comment.id}
                                                    data-type={comment.class}
                                                >
                                                    <i className="fa-solid fa-flag"></i>
                                                    Report
                                                </button> :
                                                comment.reported_at &&
                                                <div className="dropdown-item d-flex align-items-center gap-2 mb-0 text-sm py-2">
                                                    <i className="fa-solid fa-flag"></i>
                                                    <span>Reported {comment.reported_at}</span>
                                                </div>
                                        }
                                    </li>
                                </ul>
                            </div>
                        }
                    </div>
                    <Edit comment={comment} update={update} setOnEdit={setOnEdit} onEdit={onEdit}/>
                    <div className="d-flex align-items-center gap-2 mt-1">
                        <div className="d-flex align-items-center gap-2">
                            {
                                user ?
                                    <Interaction
                                        active={JSON.stringify({'like': comment.liked_by_auth_user, 'dislike': comment.disliked_by_auth_user})}
                                        model={comment.model}
                                        target={comment.id}
                                        count={JSON.stringify({'likes_count' : comment.likes_count, 'dislikes_count' : comment.dislikes_count})}
                                        auth={user}
                                    />
                                    :
                                    <div className="d-flex justify-content-between bg-light-dark rounded-4">
                                        <button
                                            className="hover-grey btn btn-sm border border-0 text-black px-3 rounded-5 rounded-end d-fl"
                                            data-bs-toggle="popover"
                                            data-bs-placement="left"
                                            data-bs-title="Like this comment ?"
                                            data-bs-trigger="focus"
                                            data-bs-html="true"
                                            data-bs-content="Sign in to make your opinion count.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                                        >
                                            <div className={'d-flex gap-2 align-items-center'}>
                                                <i className="fa-regular fa-thumbs-up"></i>
                                                {comment.likes_count > 0 && <span>{comment.likes_count}</span>}
                                            </div>
                                        </button>
                                        <div className="vr"></div>
                                        <button
                                            className="hover-grey btn btn-sm border border-0 text-black px-3 rounded-5 rounded-start"
                                            data-bs-toggle="popover"
                                            data-bs-placement="right"
                                            data-bs-title="Don't like this comment ?"
                                            data-bs-trigger="focus"
                                            data-bs-html="true"
                                            data-bs-content="Sign in to make your opinion count.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                                        >
                                            <div className={'d-flex gap-2 align-items-center'}>
                                                <i className="fa-regular fa-thumbs-down"></i>
                                                {comment.dislikes_count > 0 && <span>{comment.dislikes_count}</span>}
                                            </div>
                                        </button>
                                    </div>
                            }
                        </div>
                        {
                            canReply && <button
                                className="btn btn-sm text-info"
                                {...attributes}
                                onClick={() => user && setShowReply(true)}
                            >
                                Reply
                            </button>
                        }
                    </div>
                    {showReply && <ReplyForm setShowReply={setShowReply} comment={comment} reply={reply} user={user}/>}
                    {
                        replies.length > 0 &&
                        <button className={'btn btn-sm text-primary my-1 mt-2 ps-0 fw-bold d-flex align-items-center gap-2'} onClick={() => setShowReplies(showReplies => !showReplies)}>
                            {
                                comment.is_author_reply &&
                                <>
                                    <img className="rounded-circle img-fluid" src={comment.author.avatar} alt={comment.author.username + ' avatar'} style="width: 30px;"/>
                                    <span>•</span>
                                </>
                            }
                            <i className={'fa-solid fa-' + (showReplies ? 'chevron-up' : 'chevron-down')}></i>
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
                                    user={user}
                                    canReply={false}
                                    remove={deleteReply}
                                    update={updateReply}
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
