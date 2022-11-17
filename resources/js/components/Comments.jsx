import { useState, useEffect } from 'preact/hooks';
import Comment from "./Comment";

export default function Comments ({target, auth}) {

    const [comments, setComments] = useState([]);
    const [loading, setLoading] = useState(false);
    const [selectedSort, setSelectedSort] = useState('top');

    useEffect(async () => {
        setLoading(true);
        const response = await fetch(`/api/comments/${target}`);
        const data = await response.json()
        setLoading(false);
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

    const sort = async (type) => {
        setSelectedSort(type)
        setLoading(true);
        const response = await fetch(`/api/comments/${target}?sort=${type}`);
        const data = await response.json()
        setLoading(false);
        setComments(data.data)
    }

    const activeButton = (type) => selectedSort === type ? 'primary ' : 'outline-primary ';

    return (
        <div className="mb-4">
            <div className="mb-3 d-flex align-items-cente justify-content-between">
                <div>{comments.length} Commentaires</div>
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
            {
                (loading) ?
                    <div className={'text-center mt-3'}>
                        <div className="spinner-border" role="status">
                            <span className="visually-hidden">Loading...</span>
                        </div>
                    </div>
                : comments.map(comment => <Comment key={comment.id} comment={comment} auth={auth} canReply={true}/>)
            }
        </div>
    )
}
