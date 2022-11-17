import { useState } from 'preact/hooks';

export default function Like ({model, target, count, active, auth}) {

    const {like, dislike} = JSON.parse(active)
    const {likes_count, dislikes_count} = JSON.parse(count)

    const [liked, setLiked] = useState(like);
    const [disliked, setDisLiked] = useState(dislike);
    const [counterLike, setCounterLike] = useState(likes_count);
    const [counterDislike, setCounterDislike] = useState(dislikes_count);

    const handleClick = (type) => {

        if (type === 'like') {
            setLiked(liked => !liked)
            setDisLiked(false)
            liked ? setCounterLike(counter => counter - 1) : setCounterLike(counter => counter + 1);
            disliked ? setCounterDislike(counter => counter - 1) : null;
        } else {
            setDisLiked(liked => !liked)
            setLiked(false)
            disliked ? setCounterDislike(counter => counter - 1) : setCounterDislike(counter => counter + 1);
            liked ? setCounterLike(counter => counter - 1) : null;
        }

        const response = fetch(`/api/${type}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify({
                'model': model,
                'id': target,
            })
        });
    }




    /*
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))


    let attributes =  {
        ...(!auth && {
            'data-bs-toggle': "popover",
            'data-bs-placement': "left",
            'data-bs-title': "Vous aimez ce commentaire ?",
            'data-bs-trigger': "focus",
            'data-bs-html': "true",
            'data-bs-content': "Connectez-vous pour donner votre avis.<hr><a href='/login' class='btn btn-primary btn-sm'>Se connecter</a>",
        }),
        ...(auth && {
            'onClick': handleClick
        })
    }
    */

    const isActiveClass = liked ? 'btn': 'btn-outline';
    const isActiveIcon = liked ? 'fa-solid': 'fa-regular';
    //const className = type === 'like' ? `${isActiveClass}-success` : `${isActiveClass}-danger`;
    //const icon = isActiveIcon + (type === 'like' ? ' fa-thumbs-up' : ' fa-thumbs-down');

    return (
        <div className={'d-flex justify-content-between gap-1'}>
            <button
                onClick={() => handleClick('like')}
                className={'btn btn-sm ' + (liked ? 'btn' : 'btn-outline') + '-success'}
            >
                {/*<i className={icon}></i>*/}
                {/*<img src={'/storage/images/' + icon} style={{width: '18px'}}/>*/}
                J'aime
                {counterLike > 0 && <span className={'ml-1'}>{counterLike}</span>}
            </button>
            <button
                onClick={() => handleClick('dislike')}
                className={'btn btn-sm ' + (disliked ? 'btn' : 'btn-outline') + '-danger'}
            >
                {/*<i className={icon}></i>*/}
                {/*<img src={'/storage/images/' + icon} style={{width: '18px'}}/>*/}
                J'aime pas
                {counterDislike > 0 && <span className={'ml-1'}>{counterDislike}</span>}
            </button>
        </div>
    )
}
