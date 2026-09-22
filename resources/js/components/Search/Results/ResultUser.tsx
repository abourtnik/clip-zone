import clsx from 'clsx';
import numeral from "numeral";
import {useTranslation} from "react-i18next";
import {SearchUserType} from "@/types";
import ImageLoaded from "@/components/Images/ImageLoaded";

type ResultItemUserProps = {
    result: SearchUserType
}

export function ResultUser ({result} : ResultItemUserProps) {

    const { t } = useTranslation();

    return (
        <>
            <ImageLoaded
                source={result.avatar}
                class={clsx('img-fluid rounded-circle tw:w-10')}
                alt="user avatar"
            />
            <div className={'text-sm text-break d-flex flex-column gap-1 align-items-start'}>
                <div className={'text-black'} dangerouslySetInnerHTML={{__html: result._formatted.username}}></div>
                {
                    result.subscribers &&
                    <div className={'text-muted'}>{t( 'Subscribers', { count: result.subscribers, formatted: numeral(result.subscribers).format('0.[0]a') } )}</div>
                }
            </div>
        </>
    )
}
