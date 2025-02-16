import {memo} from 'preact/compat';
import {NotificationType, Paginator} from "@/types";
import moment from "moment/moment";
import {Dropdown} from 'react-bootstrap';
import {useErrorMutation} from "@/hooks";
import {deleteNotification, handleNotification} from "@/api/clipzone";
import {InfiniteData, useQueryClient} from "@tanstack/react-query";
import {produce} from "immer";

type Props = {
    notification: NotificationType,
    setUnread: (value: number | ((prev: number) => number)) => void
}

const Notification = memo(({notification, setUnread} : Props) => {

    const queryClient = useQueryClient();

    const {mutate: remove}= useErrorMutation({
        mutationFn: () => deleteNotification(notification.id),
        onMutate: () => {
            queryClient.setQueryData(['notifications'], (oldData: InfiniteData<Paginator<NotificationType>> | undefined) => {
                if (!oldData) return undefined;
                return produce(oldData, draft => {
                    draft.pages.forEach(page => {
                        page.data = page.data.filter(n => n.id !== notification.id);
                    });
                });
            });
            if (!notification.is_read) {
                setUnread(v => v - 1);
            }

        }
    });

    const {mutate: handle}= useErrorMutation({
        mutationFn: (type: 'read' | 'unread') => handleNotification(notification.id, type),
        onMutate: () => {
            queryClient.setQueryData(['notifications'], (oldData: InfiniteData<Paginator<NotificationType>> | undefined) => {
                if (!oldData) return undefined;
                return produce(oldData, draft => {
                    draft.pages.forEach(page => {
                        page.data.forEach(n => {
                            if (n.id === notification.id) {
                                n.is_read = !n.is_read
                            }
                        });
                    });
                });
            })
            notification.is_read ? setUnread(v => v + 1) : setUnread(v => v - 1);
        }
    });

    return (
        <div className="position-relative d-flex align-items-center list-group-item gap-3 hover-primary">
            <a href={`/profile/notifications/${notification.id}/click`} className="text-decoration-none text-black w-100 align-items-center">
                <div className="position-relative d-flex align-items-center justify-content-between">
                    <div>
                        <p className="mb-0 text-sm text-break">{notification.message}</p>
                        <p className="text-muted text-sm mb-0 mt-2">{moment(notification.created_at).fromNow()}</p>
                    </div>
                </div>
                <span style="position: absolute;inset: 0;"></span>
            </a>
            {!notification.is_read && <span className="bg-primary rounded-circle" style="width: 12px;height: 10px"/> }
            <Dropdown>
                <Dropdown.Toggle variant="transparent" id="dropdown-basic" className="without-caret btn-sm py-1 px-2">
                    <i className="fa-solid fa-ellipsis-vertical"></i>
                </Dropdown.Toggle>
                <Dropdown.Menu>
                    {notification.is_read && <Dropdown.Item as={'button'} onClick={() => handle('unread')}>Mark as unread</Dropdown.Item>}
                    {!notification.is_read && <Dropdown.Item as={'button'}  onClick={() => handle('read')}>Mark as read</Dropdown.Item>}
                    <Dropdown.Item as={'button'} onClick={() => remove()}>Delete</Dropdown.Item>
                </Dropdown.Menu>
            </Dropdown>
        </div>
    )
}, (prev, next) => prev.notification.is_read === next.notification.is_read);

export default Notification;
