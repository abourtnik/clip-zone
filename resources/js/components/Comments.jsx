import { useState, useEffect } from 'preact/hooks';
import Comment from "./Comment";

export default function Comments ({target, auth}) {

    const [comments, setComments] = useState([]);

    useEffect(async () => {
        const response = await fetch(`/api/comments/${target}`);
        const data = await response.json()
        setComments(data.data)
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

        document.getElementById('content').value = '';
    }

    return (
        <div className="mb-4">
            <div className="mb-3 d-flex align-items-cente justify-content-between">
                <div>{comments.length} Commentaires</div>
                <div className={'d-flex gap-2 align-items-center'}>
                    <button className={'btn btn-primary btn-sm'}>Top des commentaires</button>
                    <button className={'btn btn-outline-primary btn-sm'}>Les plus recents d'abord</button>
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
                        <button type="submit" className="btn btn-primary">
                            Ajouter un commentaire
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
            {comments.map(comment => <Comment key={comment.id} comment={comment} auth={auth} canReply={true}/>)}
        </div>
    )
}
