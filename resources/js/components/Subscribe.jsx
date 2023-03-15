import { useState } from 'preact/hooks';
import {jsonFetch} from '../hooks'

export default function Subscribe ({isSubscribe = true, user, size= null}) {

    const [subscribe, setSubscribe] = useState(isSubscribe);

    const onClick = async () => {

        setSubscribe(subscribe => !subscribe)

        await jsonFetch(`/api/subscribe/${user}`, {
            method: 'POST',
        })
    }

    const className = (subscribe ? 'btn-info text-white' : 'btn-danger') + (size ? ' btn-' + size : '');

    return (
        <button onClick={onClick} className={'btn ' + className}>
            { subscribe ? 'Subscribed' : 'Subscribe'}
        </button>
    )
}
