import { useState } from 'preact/hooks';
import {ThumbsUpSolid, ThumbsUpRegular, ThumbsDownSolid, ThumbsDownRegular} from './Icon'

export default function Interaction ({model, target, count, active, showCount}) {

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
        <div className={'d-flex justify-content-between gap-1'}>
            <button
                onClick={() => handleClick('like')}
                className={'d-flex justify-content-between align-items-center btn btn-sm  ' + (liked ? 'btn' : 'btn-outline') + '-success'}
            >
                {liked ? <ThumbsUpRegular/> : <ThumbsUpSolid/>}
                { (showCount === 'true' && counterLike > 0) && <span className={'ml-1'}>{counterLike}</span>}
            </button>
            <button
                onClick={() => handleClick('dislike')}
                className={'d-flex justify-content-between align-items-center btn btn-sm ' + (disliked ? 'btn' : 'btn-outline') + '-danger'}
            >
                {disliked ? <ThumbsDownRegular/> : <ThumbsDownSolid/>}
                { (showCount === 'true' && counterDislike > 0) && <span className={'ml-1'}>{counterDislike}</span>}
            </button>
        </div>
    )
}
