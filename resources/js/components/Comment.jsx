import { useState } from 'preact/hooks';

import Like from "./Like";

export default function Comment ({comment, auth, canReply}) {

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

    const showRepliesText = (show, length) => {
        const count = length > 1 ? 'les ' + replies.length + ' réponses' : 'la réponse';
        const text = show ? 'Masquer ' + count : 'Afficher ' + count;
        return text;
    }

    return (
        <div className="row mb-3">
            <a className={"col-1"} href={comment.user.route}>
                <img className="rounded-circle img-fluid" src={comment.user.avatar} alt={comment.user.username + ' avatar'} style="width: 50px;"/>
            </a>
            <div className={'col-11'}>
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
                                    <button className={"btn btn-sm text-primary p-0"}>Modifier</button>
                                    <button className={"btn btn-sm text-danger p-0"}>Supprimer</button>
                                </>
                            }
                            {
                                comment.is_updated &&
                                <small className="text-muted fw-semibold">Modifié</small>
                            }
                        </div>
                        {

                        }

                    </div>
                    <div className={'my-3'}>{comment.content}</div>
                    <div className="d-flex align-items-center gap-2 mt-1">
                        <div className="d-flex align-items-center gap-2">
                            <Like type={'like'} model={comment.model} target={comment.id} count={comment.likes_count} active={comment.isLiked} auth={auth}/>
                        </div>
                        {
                            canReply &&  <button
                                className="btn btn-sm text-info"
                                {...attributes}
                                onClick={() => auth && setShowReply(true)}
                            >
                                Répondre
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
                                    Annuler
                                </button>
                                <button type="submit" className="btn btn-primary btn-sm">
                                    Répondre
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
                        {replies.map(comment => <Comment key={comment.id} comment={comment} auth={auth} canReply={false}/>)}
                    </div>
                }
            </div>
        </div>
    )
}
