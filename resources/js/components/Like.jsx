import { useState } from 'preact/hooks';

export default function Like ({type, model, target, count, active, auth}) {

    const [liked, setLiked] = useState(active);
    const [counter, setCounter] = useState(count);

    const handleClick = () => {

        setLiked(liked => !liked)
        liked ? setCounter(counter => counter - 1) : setCounter(counter => counter + 1);
        //setDisliked(false)

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

    const isActiveClass = liked ? 'btn': 'btn-outline';
    const isActiveIcon = liked ? 'fa-solid': 'fa-regular';
    const className = type === 'like' ? `${isActiveClass}-success` : `${isActiveClass}-danger`;
    const icon = isActiveIcon + (type === 'like' ? ' fa-thumbs-up' : ' fa-thumbs-down');

    //const text = liked ? 'Aimé'

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

    return (
        <div className={'d-flex justify-content-between gap-1'}>
            <button
                {...attributes}
                className={'btn btn-sm ' + className}
            >
                {/*<i className={icon}></i>*/}
                {/*<img src={'/storage/images/' + icon} style={{width: '18px'}}/>*/}
                {'Aime'}
                {counter > 0 && <span className={'ml-1'}>{counter}</span>}
            </button>
        </div>
    )
}
