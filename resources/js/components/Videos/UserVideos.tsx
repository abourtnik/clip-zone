import { useState } from 'preact/hooks';
import {VideosList} from "@/components/Videos";
import {useTranslation} from "react-i18next";
import {VideosSort} from "@/types";

type Props = {
    user: number
}

export function UserVideos ({user} : Props) {

    const { t } = useTranslation();

    const [sort, setSort] = useState<VideosSort>('latest');

    const activeButton = (type : VideosSort) => sort === type ? 'primary ' : 'outline-primary ';

    const selectSort = async (type: VideosSort) => {
        if (type !== sort) {
            setSort(type)
        }
    }

    return (
        <>
            <div className="d-flex align-items-center gap-2 my-4">
                <button onClick={() => selectSort('latest')} className={'btn btn-' + activeButton('latest') + 'btn-sm'} type="button">{t('Latest')}</button>
                <button onClick={() => selectSort('popular')} className={'btn btn-' + activeButton('popular') + 'btn-sm'} type="button">{t('Popular')}</button>
                <button onClick={() => selectSort('oldest')} className={'btn btn-' + activeButton('oldest') + 'btn-sm'} type="button">{t('Oldest')}</button>
            </div>
            <VideosList url={`/api/users/${user}/videos?sort=${sort}`}/>
        </>

    )
}
