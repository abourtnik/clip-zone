import { memo } from 'preact/compat';
import {useToggle} from "../../hooks";
import {useTranslation} from "react-i18next";

const Expand = memo(({comment}) => {

    const { t } = useTranslation();

    const [expand, setExpand] = useToggle(false);

    return (
        <div className={comment.is_long ? 'my-2' : 'mt-2 mb-3'} style={{whiteSpace: 'pre-line'}}>
            <div className={'text-sm'} dangerouslySetInnerHTML={{__html: expand ? comment.parsed_content : comment.short_content}}></div>
            {comment.is_long && <button onClick={setExpand} className={'text-primary bg-transparent ps-0 text-sm mt-1'}>{expand ? t('Show less'): t('Read more')}</button>}
        </div>
    )
});

export default Expand
