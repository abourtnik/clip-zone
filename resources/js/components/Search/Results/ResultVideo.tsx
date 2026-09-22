import clsx from 'clsx';
import {SearchVideoType} from "@/types";
import ImageLoaded from "@/components/Images/ImageLoaded";

type ResultItemUserProps = {
    result: SearchVideoType
}

export function ResultVideo ({result} : ResultItemUserProps) {
    return (
        <>
            <ImageLoaded
                source={result.thumbnail}
                class={clsx('img-fluid d-block tw:w-[100px] object-fit-cover')}
                alt="video thumbnail"
            />
            <div className={'text-sm text-break'}>
                <div className={'text-black'} dangerouslySetInnerHTML={{__html: result._formatted.title}}></div>
                <div className={'text-muted'} dangerouslySetInnerHTML={{__html: result._formatted.user}}></div>
            </div>
        </>
    )
}
