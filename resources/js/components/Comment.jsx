import {useRef, useState} from 'preact/hooks';
import { memo } from 'preact/compat';

import Like from "./Like";

const Comment = memo(({comment, auth, canReply, deleteComment, updateComment}) => {

    const [onEdit, setOnEdit] = useState(false);
    const [showReply, setShowReply] = useState(false);
    const [replies, setReplies] = useState(comment.replies);
    const [showReplies, setShowReplies] = useState(false);

    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

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
                target: parseInt(comment.video.id),
                parent: comment.id
            })
        });

        const new_reply = await response.json();

        setReplies(replies => [new_reply.data, ...replies]);
        setShowReplies(true);
        setShowReply(false);

        document.getElementById('content-' + comment.id).value = '';
    }

    let attributes =  {
        ...(!auth && {
            'data-bs-toggle': "popover",
            'data-bs-placement': "left",
            'data-bs-title': "Vous souhaitez répondre à ce commentaire ?",
            'data-bs-trigger': "focus",
            'data-bs-html': "true",
            'data-bs-content': "Connectez-vous pour répondre.<hr><a href='/login' class='btn btn-primary btn-sm'>Se connecter</a>",
        })
    }

    const update = async (comment) => {
        await updateComment(comment, textarea.current.value)
        setOnEdit(false);
    }


    const showRepliesText = (show, length) => {
        const count = length > 1 ? 'les ' + replies.length + ' replies' : 'reply';
        const text = show ? 'Hide ' + count : 'Show ' + count;
        return text;
    }

    const textarea = useRef(null)

    return (
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
                            {
                                comment.user.id === parseInt(auth) &&
                                <>
                                    <button onClick={() => setOnEdit(onEdit => !onEdit)} className={"btn btn-sm text-primary p-0"}>Update</button>
                                    <button onClick={() => deleteComment(comment)} className={"btn btn-sm text-danger p-0"}>Delete</button>
                                </>
                            }
                            {
                                comment.is_updated &&
                                <small className="text-muted fw-semibold">Modified</small>
                            }
                        </div>
                        {

                        }

                    </div>
                    {
                        onEdit ?
                            <div className={'my-3'}>
                                <textarea className="form-control" rows="1" name="content" ref={textarea} required>{comment.content}</textarea>
                                <div className={'d-flex gap-1 mt-2'}>
                                    <button onClick={() => update(comment)} className={'btn btn-primary btn-sm'}>Update</button>
                                    <button onClick={() => setOnEdit(false)} className={'btn btn-secondary btn-sm'}>Cancel</button>
                                </div>
                            </div>
                            : <div className={'my-3'}>{comment.content}</div>
                    }
                    <div className="d-flex align-items-center gap-2 mt-1">
                        <div className="d-flex align-items-center gap-2">
                            <Like
                                active={JSON.stringify({'like': comment.liked_by_auth_user, 'dislike': comment.disliked_by_auth_user})}
                                model={comment.model}
                                target={comment.id}
                                count={JSON.stringify({'likes_count' : comment.likes_count, 'dislikes_count' : comment.dislikes_count})}
                                auth={auth}
                            />
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
                                <textarea className="form-control" id={"content-" + comment.id} rows="1" name="content" placeholder="Répondre au commentaire ..." required></textarea>
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
                        {replies.map(comment => <Comment key={comment.id} comment={comment} auth={auth} canReply={false} deleteComment={deleteComment} updateComment={updateComment}/>)}
                    </div>
                }
            </div>
        </div>
    )
});

export default Comment;
