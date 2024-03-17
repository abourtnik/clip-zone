import {useState, useEffect, useCallback} from 'preact/hooks';
import {memo} from 'preact/compat';
import {jsonFetch} from "../hooks";
import Notification from "./Notification";

const Notifications = memo(({initial}) => {

    const [notifications, setNotifications] = useState(JSON.parse(initial));

    useEffect( async () => {
        window.PRIVATE_CHANNEL.notification(notification => {
            setNotifications(notifications => [notification, ...notifications]);
            document.getElementById('bell').classList.add('fa-shake')
            new Audio('/sounds/notification.mp3').play()
            setTimeout(() => {
                document.getElementById('bell').classList.remove('fa-shake')
            }, 1000)
        });
    }, []);

    useEffect(() => {

        const unread = notifications.filter(notification => !notification.is_read).length;

        if (unread) {
            document.getElementById('notifications_count').classList.remove('d-none')
            document.querySelector('#notifications_count span').innerText = unread;
        } else {
            document.getElementById('notifications_count').classList.add('d-none')
        }

    }, [notifications]);

    const read = useCallback (async (notification) => {
        return jsonFetch(`/api/notifications/${notification.id}/read`).then(() => {
            setNotifications(notifications => notifications.map(n => n.id === notification.id ? {...n, is_read: true} : n));
        }).catch(e => e);
    }, []);

    const readAll = () => {
        jsonFetch(`/api/notifications/read-all`).then(() => {
            setNotifications(notifications => notifications.map(n => ({...n, is_read: true})))
        }).catch(e => e);
    }

    const unread = notifications.filter(notification => !notification.is_read).length;

    return (
        <>
            <div className="offcanvas offcanvas-end" tabIndex="-1" id="notifications" aria-labelledby="notifications" data-bs-backdrop="true" style={{width: '350px'}}>
                <div className="offcanvas-header bg-light border d-flex justify-content-between align-items-center py-2">
                    <h6 className="offcanvas-title">
                        Notifications { unread > 0 && <span>({unread})</span> }
                    </h6>
                    <div className="d-flex gap-1 align-items-center">
                        {
                            unread > 0 &&
                            <button className="btn btn-primary btn-sm text-decoration-none" onClick={() => readAll()}>
                                <small>Mark all as read</small>
                            </button>
                        }
                        <button type="button" className="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                </div>
                <div className="offcanvas-body px-0 pt-0 overflow-auto" style="min-height: 500px">
                    {
                        notifications.length ?
                            <ul className="list-group list-group-flush overflow-auto">
                                {
                                    notifications.map(notification => <Notification key={notification.id} notification={notification} read={read}/>)
                                }
                            </ul>
                            :
                            <div className="d-flex flex-column justify-content-center align-items-center p-5">
                                <i className="fa-solid fa-bell-slash fa-2x"></i>
                                <h5 className="mt-3 fs-6">Your notifications live here</h5>
                                <p className="text-muted text-center text-sm">Subscribe to your favorite channels to get notified about their latest videos.</p>
                            </div>
                    }
                </div>
                <div className="bg-light border w-100 text-center py-2">
                    <a className="btn-link text-primary text-decoration-none text-sm fw-bold" href={'/profile/notifications'}>See All Notifications </a>
                </div>
            </div>
        </>
    )
})

export default Notifications;
