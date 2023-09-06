import { useState } from 'preact/hooks';
import Videos from "./Videos";

export default function UserVideos ({user, videos, showSort = true, excludePinned = true}) {

    const [selectedSort, setSelectedSort] = useState('latest');

    const activeButton = (type) => selectedSort === type ? 'primary ' : 'outline-primary ';

    const sort = async (type) => {
        setSelectedSort(type)
    }

    const pinned = excludePinned && '&excludePinned';

    return (
        <>
            {
                showSort &&
                <div className="d-flex align-items-center gap-2 my-4">
                    <button onClick={() => sort('latest')} className={'btn btn-' + activeButton('latest') + 'btn-sm'} type="button">Latest</button>
                    <button onClick={() => sort('popular')} className={'btn btn-' + activeButton('popular') + 'btn-sm'} type="button">Popular</button>
                    <button onClick={() => sort('oldest')} className={'btn btn-' + activeButton('oldest') + 'btn-sm'} type="button">Oldest</button>
                </div>
            }
            <Videos url={`/api/videos/user/${user}?sort=${selectedSort}${pinned}`} skeletons={videos}/>
        </>

    )
}
