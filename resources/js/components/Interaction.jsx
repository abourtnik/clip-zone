import { useState } from 'preact/hooks';
import {jsonFetch} from '../hooks'

export default function Interaction ({model, target, count, active, showCount = true}) {

    const {like, dislike} = JSON.parse(active)
    const {likes_count, dislikes_count} = JSON.parse(count)

    const [liked, setLiked] = useState(like);
    const [disliked, setDisLiked] = useState(dislike);
    const [counterLike, setCounterLike] = useState(likes_count);
    const [counterDislike, setCounterDislike] = useState(dislikes_count);

    const handleClick = async (type) => {

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

        await jsonFetch(`/api/${type}` , {
            method: 'POST',
            body: JSON.stringify({
                'model': model,
                'id': target,
            })
        }).catch(e => e)
    }

    return (
        <div className={'d-flex justify-content-between bg-light-dark rounded-4'}>
            <button
                onClick={() => handleClick('like')}
                className={'hover-grey btn btn-sm border border-0 px-3 rounded-5 rounded-end ' + (liked ? 'text-success' : 'text-black') + (!showCount || counterLike === 0 ? ' py-2' : '')}
                data-bs-toggle="tooltip"
                data-bs-title="I like this"
                data-bs-placement="bottom"
                data-bs-trigger="hover"
            >
                <div className={'d-flex gap-1 align-items-center'}>
                    {liked ? <i className="fa-solid fa-thumbs-up"></i> : <i className="fa-regular fa-thumbs-up"></i>}
                    { (showCount && counterLike > 0) && <span className={'ml-1'}>{counterLike}</span>}
                </div>
            </button>
            <div className="vr h-75 my-auto"></div>
            <button
                onClick={() => handleClick('dislike')}
                className={'hover-grey btn btn-sm border border-0 px-3 rounded-5 rounded-start ' + (disliked ? 'text-danger' : 'text-black')}
                data-bs-toggle="tooltip"
                data-bs-title="I dislike this"
                data-bs-placement="bottom"
                data-bs-trigger="hover"
            >
                <div className={'d-flex gap-1  align-items-center'}>
                    {disliked ? <i className="fa-solid fa-thumbs-down"></i> : <i className="fa-regular fa-thumbs-down"></i>}
                    { (showCount && counterDislike > 0) && <span className={'ml-1'}>{counterDislike}</span>}
                </div>
            </button>
        </div>
    )
}
