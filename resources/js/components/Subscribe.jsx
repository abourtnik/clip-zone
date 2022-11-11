import { useState } from 'preact/hooks';

export default function Subscribe ({issubscribe, user}) {

    const [subscribe, setSubscribe] = useState( issubscribe === 'true');

    const onClick = () => {

        setSubscribe(subscribe => !subscribe)

        const response = fetch(`/api/follow/${user}`, {
            method: 'POST',
            credentials: 'include'
        });
    }

    const className = subscribe ? 'btn-info text-white' : 'btn-danger';

    return (
        <button onClick={onClick} className={'btn ' + className}>
            { subscribe ? 'Abonné' : 'S\'abonner'}
        </button>
    )
}
