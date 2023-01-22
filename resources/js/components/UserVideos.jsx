import { useState } from 'preact/hooks';
import Videos from "./Videos";

export default function UserVideos ({user, videos, showSort = true}) {

    const [selectedSort, setSelectedSort] = useState('recent');

    const activeButton = (type) => selectedSort === type ? 'primary ' : 'outline-primary ';

    const sort = async (type) => {
        setSelectedSort(type)
    }

    return (
        <>
            {
                showSort &&
                <div className="d-flex align-items-center gap-2 my-4">
                    <button onClick={() => sort('recent')} className={'btn btn-' + activeButton('recent') + 'btn-sm'} type="button">Recently uploaded</button>
                    <button onClick={() => sort('popular')} className={'btn btn-' + activeButton('popular') + 'btn-sm'} type="button">Popular</button>
                </div>
            }
            <Videos url={"/api/videos/user/" + user + '?sort=' + selectedSort} skeletons={videos}/>
        </>

    )
}
