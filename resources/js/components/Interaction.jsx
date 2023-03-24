import { useState } from 'preact/hooks';
import {ThumbsUpSolid, ThumbsUpRegular, ThumbsDownSolid, ThumbsDownRegular} from './Icon'
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
        })
    }

    return (
        <div className={'d-flex justify-content-between bg-light-dark border border-secondary rounded-4'}>
            <button
                onClick={() => handleClick('like')}
                className={'hover-grey d-flex gap-2 align-items-center btn btn-sm border border-0 px-3 rounded-5 rounded-end ' + (liked ? 'text-success' : 'text-black')}
                data-bs-toggle="tooltip"
                data-bs-title="I like this"
                data-bs-placement="bottom"
                data-bs-trigger="hover"
            >
                {liked ? <ThumbsUpRegular width={'16px'}/> : <ThumbsUpSolid width={'16px'}/>}
                { (showCount && counterLike > 0) && <span className={''}>{counterLike}</span>}
            </button>
            <div className="vr"></div>
            <button
                onClick={() => handleClick('dislike')}
                className={'hover-grey d-flex gap-2 align-items-center btn btn-sm border border-0 px-3 rounded-5 rounded-start ' + (disliked ? 'text-danger' : 'text-black')}
                data-bs-toggle="tooltip"
                data-bs-title="I dislike this"
                data-bs-placement="bottom"
                data-bs-trigger="hover"
            >
                {disliked ? <ThumbsDownRegular width={'16px'}/> : <ThumbsDownSolid width={'16px'}/>}
                { (showCount && counterDislike > 0) && <span className={''}>{counterDislike}</span>}
            </button>
        </div>
    )
}
