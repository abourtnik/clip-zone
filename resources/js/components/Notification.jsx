import {useState} from 'preact/hooks';
import {memo} from 'preact/compat';

const Notification = memo(({notification, read}) => {

    const [loading, setLoading] = useState(false);

    const handleRead= async (e) => {

        const tooltip = bootstrap.Tooltip.getInstance(e.currentTarget)
        setLoading(true);
        read(notification).then(() => tooltip.hide()).catch(e => e).finally(() => {
            setLoading(false);
        })
    }

    return (
        <div className="position-relative d-flex align-items-center list-group-item justify-content-between gap-4 hover-primary">
            <a href={notification.url} className="text-decoration-none text-black w-100 align-items-center">
                <div className="position-relative d-flex align-items-center justify-content-between">
                    <div>
                        <p className="mb-0 text-sm text-break">{notification.message}</p>
                        <p className="text-muted text-sm mb-0 mt-2">{notification.created_at}</p>
                    </div>
                </div>
                <span style="position: absolute;inset: 0;"></span>
            </a>
            {
                !notification.is_read &&
                <>
                    <span className="bg-primary rounded-circle" style="width: 12px;height: 10px"/>
                    <button
                        className="btn bg-success bg-opacity-25 rounded-circle p-2 btn-sm position-relative border border-1 d-flex align-items-center justify-content-center fw-bold"
                        type="button"
                        onClick={handleRead}
                        data-bs-toggle="tooltip"
                        data-bs-title="Mark as read"
                    >
                        {
                            loading ?
                                <span className="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                                : <i className="fa-solid fa-check"></i>
                        }
                    </button>
                </>
            }
        </div>
    )
})

export default Notification;
