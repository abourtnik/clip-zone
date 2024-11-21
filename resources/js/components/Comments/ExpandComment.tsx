import { memo } from 'preact/compat';
import {useTranslation} from "react-i18next";
import {CommentType} from "@/types";
import {useState} from "preact/hooks";

type Props = {
    comment: CommentType
}

export const Expand = memo(({comment} : Props) => {

    const { t } = useTranslation();

    const [expand, setExpand] = useState<boolean>(false);

    return (
        <div className={comment.is_long ? 'my-2' : 'mt-2 mb-3'} style={{whiteSpace: 'pre-line'}}>
            <div className={'text-sm'} dangerouslySetInnerHTML={{__html: expand ? comment.parsed_content : comment.short_content}}></div>
            {comment.is_long && <button onClick={() => setExpand(v => !v)} className={'text-primary bg-transparent ps-0 text-sm mt-1'}>{expand ? t('Show less'): t('Read more')}</button>}
        </div>
    )
});
