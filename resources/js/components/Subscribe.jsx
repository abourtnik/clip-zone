import { useState } from 'preact/hooks';

export default function Subscribe ({issubscribe, user, size= null}) {

    const [subscribe, setSubscribe] = useState( issubscribe === 'true');

    const onClick = async () => {

        setSubscribe(subscribe => !subscribe)

        const response = await fetch(`/api/follow/${user}`, {
            method: 'POST',
            credentials: 'include'
        });

        const data = await response.json();
    }

    const className = subscribe ? 'btn-info text-white' : 'btn-danger' + (size && ' btn-' + size);

    return (
        <button onClick={onClick} className={'btn ' + className}>
            { subscribe ? 'Abonné' : 'S\'abonner'}
        </button>
    )
}
