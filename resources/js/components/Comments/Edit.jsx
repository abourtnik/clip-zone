import {useState, useRef} from "preact/hooks";
import Button from '../Button'
import { memo } from 'preact/compat';
import Expand from "./Expand";
import {useTranslation} from "react-i18next";

const Edit = memo(({comment, update, setOnEdit, onEdit}) => {

    const { t } = useTranslation();

    const [loading, setLoading] = useState(false);

    const textarea = useRef(null)

    const handleUpdate = async () => {
        setLoading(true);
        update(comment, textarea.current.value).then(() => {
            setOnEdit(false)
        }).catch(e => e).finally(() => {
            setLoading(false);
        });
    }

    return (
        <>
            {
                onEdit ?
                    <div className={'my-3'}>
                        <textarea className="form-control" rows="3" name="content" ref={textarea} required>{comment.content}</textarea>
                        <div className={'d-flex gap-1 mt-2'}>
                            <Button loading={loading} onClick={handleUpdate}>
                                {t('Save')}
                            </Button>
                            <button disabled={loading} onClick={() => setOnEdit(false)} className={'btn btn-secondary btn-sm'}>{t('Cancel')}</button>
                        </div>
                    </div>
                    : <Expand comment={comment}/>
            }
        </>
    )
});

export default Edit
