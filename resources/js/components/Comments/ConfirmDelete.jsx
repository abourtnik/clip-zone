import { useState } from 'preact/hooks';
import {useTranslation} from "react-i18next";

export default function ConfirmDelete ({onDelete, comment}) {

    const { t } = useTranslation()

    const [confirmDelete, setConfirmDelete] = useState(false);

    return (
        <li>
            {
                confirmDelete ?
                    <div className="dropdown-item d-flex gap-3">
                        <button className={'d-flex align-items-center gap-2 btn btn-sm btn-danger'} onClick={() => onDelete(comment)}>
                            <i className="fa-solid fa-trash-can"></i>
                            <span>{t('Confirm delete ?')}</span>
                        </button>
                        <button className={'btn btn-sm btn-primary fw-bold'} onClick={() => setConfirmDelete(false)}>{t('Cancel')}</button>
                    </div>
                    :
                    <button className="dropdown-item d-flex align-items-center gap-3" onClick={() => setConfirmDelete(true)}>
                        <i className="fa-solid fa-trash-can"></i>
                        {t('Remove')}
                    </button>
            }
        </li>
    )
}
