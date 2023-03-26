import {useState, useEffect, useCallback} from 'preact/hooks';
import {memo} from 'preact/compat';
import Echo from "laravel-echo";
import Pusher from 'pusher-js';
import {Bell} from './Icon'
import {jsonFetch} from "../hooks";
import Notification from "./Notification";

const Notifications = memo(({initial}) => {

    const [notifications, setNotifications] = useState(JSON.parse(initial));

    useEffect( async () => {

        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true
        });

        window.Echo.private('App.Models.User.' + document.querySelector('meta[name="user_id"]').getAttribute('content')).notification(notification => {
            setNotifications(notifications => [notification, ...notifications]);
            document.getElementById('bell').classList.add('fa-shake')
            setTimeout(() => {
                document.getElementById('bell').classList.remove('fa-shake')
            }, 1000)
        });
    }, []);

    const read = useCallback (async (notification) => {
        return jsonFetch(`/api/notifications/${notification.ID}/read`).then(() => {
            setNotifications(notifications => notifications.map(n => n.ID === notification.ID ? {...n, is_read: true} : n))
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
            <button type="button" className="nav-link bg-transparent position-relative dropdown-toggle without-caret" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <Bell id="bell"/>
                {
                    unread > 0 &&
                        <span className="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger text-sm">
                           <span>{unread > 99 ? '99+' : unread}</span>
                           <span className="visually-hidden">unread messages</span>
                        </span>
                }
            </button>
            <div className="dropdown-menu dropdown-menu-lg dropdown-menu-right border border-0 m-0 p-0 notifications">
                <div className="card border border-top-0">
                    <div className="card-header d-flex justify-content-between align-items-center">
                        <strong>Notifications</strong>
                        {
                            unread > 0 &&
                            <button type="button" className="btn-link text-primary bg-transparent text-decoration-none text-sm fw-bold" onClick={() => readAll()}>
                                Mark all as read
                            </button>
                        }
                    </div>
                    <div className="card-body p-0">
                        {
                            notifications.length ?
                                <ul className="list-group list-group-flush overflow-auto" style="max-height: 420px">
                                    {
                                        notifications.map(notification => <Notification notification={notification} read={read}/>)
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
                    <div className="card-footer text-center">
                        <a className="btn-link text-primary text-decoration-none text-sm fw-bold" href={'profile/notifications'}>See All Notifications</a>
                    </div>
                </div>
            </div>
        </>
    )
})

export default Notifications;
