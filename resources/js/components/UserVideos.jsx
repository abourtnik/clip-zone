import { useState } from 'preact/hooks';
import Videos from "./Videos";
import {useTranslation} from "react-i18next";

export default function UserVideos ({user, videos, showSort = true, excludePinned = true}) {

    const { t } = useTranslation();

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
                    <button onClick={() => sort('latest')} className={'btn btn-' + activeButton('latest') + 'btn-sm'} type="button">{t('Latest')}</button>
                    <button onClick={() => sort('popular')} className={'btn btn-' + activeButton('popular') + 'btn-sm'} type="button">{t('Popular')}</button>
                    <button onClick={() => sort('oldest')} className={'btn btn-' + activeButton('oldest') + 'btn-sm'} type="button">{t('Oldest')}</button>
                </div>
            }
            <Videos url={`/api/users/${user}/videos?sort=${selectedSort}${pinned}`} skeletons={videos}/>
        </>

    )
}
