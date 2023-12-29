import { useState } from 'preact/hooks';
import {jsonFetch} from '../hooks'
import {useTranslation} from "react-i18next";

export default function Subscribe ({isSubscribe = true, user, size= null}) {

    const { t } = useTranslation();

    const [subscribe, setSubscribe] = useState(isSubscribe);
    const [loading, setLoading] = useState(false);

    const onClick = async () => {

        setLoading(true);

        jsonFetch(`/api/subscribe/${user}`, {
            method: 'POST',
        })
            .then(() => setSubscribe(subscribe => !subscribe))
            .catch(e => e)
            .finally(() => setLoading(false))
    }

    const className = (subscribe ? 'btn-info text-white' : 'btn-danger') + (size ? ' btn-' + size : '');

    return (
        <button onClick={onClick} className={'btn ' + className} disabled={loading}>
            {loading && <span className="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>}
            { subscribe ? t('Subscribed') : t('Subscribe')}
        </button>
    )
}
