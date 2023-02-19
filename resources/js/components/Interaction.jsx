import { useState } from 'preact/hooks';
import {ThumbsUpSolid, ThumbsUpRegular, ThumbsDownSolid, ThumbsDownRegular} from './Icon'

export default function Interaction ({model, target, count, active, showCount = true}) {

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

    return (
        <div className={'d-flex justify-content-between gap-1 bg-light-dark border border-primary rounded-4 p-1'}>
            <button
                onClick={() => handleClick('like')}
                className={'d-flex justify-content-between align-items-center btn btn-sm border border-0 ' + (liked ? 'text-success' : 'text-black')}
            >
                {liked ? <ThumbsUpRegular width={'16px'}/> : <ThumbsUpSolid width={'16px'}/>}
                { (showCount && counterLike > 0) && <span className={'ml-1'}>{counterLike}</span>}
            </button>
            <div className="vr"></div>
            <button
                onClick={() => handleClick('dislike')}
                className={'d-flex justify-content-between align-items-center btn btn-sm border border-0 ' + (disliked ? 'text-danger' : 'text-black')}
            >
                {disliked ? <ThumbsDownRegular/> : <ThumbsDownSolid/>}
                { (showCount && counterDislike > 0) && <span className={'ml-1'}>{counterDislike}</span>}
            </button>
        </div>
    )
}
