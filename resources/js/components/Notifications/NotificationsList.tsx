import {useEffect, useState} from 'preact/hooks';
import Notification from "@/components/Notifications/Notification";
import {useInView} from "react-intersection-observer";
import {InfiniteData, QueryClient, QueryClientProvider, useInfiniteQuery, useQueryClient} from "@tanstack/react-query";
import {getNotifications, readAllNotifications} from "@/api/clipzone";
import {Offcanvas} from 'react-bootstrap';
import {Fragment} from "preact";
import {useErrorMutation} from "@/hooks";
import {NotificationType, Paginator} from "@/types";
import {produce} from "immer";
import {Button} from "@/components/commons";

type Props = {
    count : number
}

const Main = ({count} : Props) => {

    const [open, setOpen] = useState(false);

    const { ref, inView} = useInView();

    const queryClient = useQueryClient();

    const {
        data: notifications,
        isLoading,
        isError,
        refetch,
        isFetchingNextPage,
        fetchNextPage,
        hasNextPage,
    } = useInfiniteQuery({
        queryKey: ['notifications'],
        queryFn: ({pageParam}) => getNotifications(pageParam),
        initialPageParam: 1,
        enabled: open,
        staleTime: Infinity,
        getNextPageParam: (lastPage, allPages, lastPageParam) => {
            if (lastPage.meta.current_page === lastPage.meta.last_page) {
                return undefined
            }
            return lastPageParam + 1
        }
    });

    const addNotification = async (notification: NotificationType) => {
        const bell = document.getElementById('bell');
        queryClient.setQueryData(['notifications'], (oldData: InfiniteData<Paginator<NotificationType>> | undefined) => {
            if (!oldData) return undefined;
            return produce(oldData, draft => {
                draft.pages[0].data.unshift(notification);
            });
        })
        bell!.classList.add('fa-shake')
        await new Audio('/sounds/notification.mp3').play()
        setTimeout(() => {
            bell!.classList.remove('fa-shake')
        }, 1000)
    }

    const {mutate: readAll, isPending}= useErrorMutation({
        mutationFn: () => readAllNotifications(),
        onSuccess: () => {
            queryClient.setQueryData(['notifications'], (oldData: InfiniteData<Paginator<NotificationType>> | undefined) => {
                if (!oldData) return undefined;
                return produce(oldData, draft => {
                    draft.pages.forEach(page => {
                        page.data.forEach(n => {
                            n.is_read = true;
                        });
                    });
                });
            })
        }
    });

    const unread = notifications?.pages?.flatMap(page => page.data).filter(notification => !notification.is_read).length ?? count;

    useEffect( () => {
        window.PRIVATE_CHANNEL.notification(addNotification);
    }, []);

    useEffect( () => {
        if (inView && !isFetchingNextPage && !isError) {
            fetchNextPage()
        }
    }, [inView]);


    return (
        <>
            <button id="notifications_button" class="btn nav-link bg-transparent btn-sm d-flex align-items-center gap-2 position-relative" onClick={() => setOpen(true)}>
                <i id="bell" class="fa-solid fa-bell"></i>
                {
                    unread > 0 &&
                    <span className={'position-absolute top-10 translate-middle badge rounded-pill bg-danger text-sm'} style="left: 90%">{unread > 99 ? '99 +' : unread}</span>
                }
            </button>
            <Offcanvas show={open} onHide={() => setOpen(false)} placement="end" style={{width: '350px'}}>
                <div className="offcanvas-header bg-light border d-flex justify-content-between align-items-center py-2">
                    <h6 className="offcanvas-title">
                        Notifications {unread > 0 && <span>({unread})</span>}
                    </h6>
                    <div className="d-flex gap-1 align-items-center">
                        {
                            unread > 0 &&
                            <Button type={'button'} loading={isPending} onClick={readAll} small={true}>
                                <small>Mark all as read</small>
                            </Button>
                        }
                            <button type="button" className="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" onClick={() => setOpen(false)}></button>
                    </div>
                </div>
                <div className="offcanvas-body px-0 pt-0 overflow-auto" style="min-height: 500px">
                    {
                        isLoading &&
                        <div className={'d-flex justify-content-center align-items-center h-100'}>
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    }
                    {
                        isError &&
                        <div className={'d-flex justify-content-center align-items-center h-100'}>
                            <div class="border border-1 bg-light text-center p-3">
                                <i class="fa-solid fa-triangle-exclamation fa-3x"></i>
                                <h3 class="h5 my-3 fw-normal">Something went wrong !</h3>
                                <p class="text-muted">If the issue persists please contact us.</p>
                                <button className="btn btn-primary rounded-5 text-uppercase btn-sm" onClick={refetch}>
                                    Try again
                                </button>
                            </div>
                        </div>
                    }
                    {
                        notifications &&
                            notifications?.pages.flatMap((page => page.data)).length > 0 ?
                                <ul className="list-group list-group-flush overflow-auto h-100">
                                    {notifications.pages.map((group, i) => (
                                        <Fragment key={i}>
                                            {group.data.map((notification) => <Notification key={notification.id}
                                                                                            notification={notification}/>)}
                                        </Fragment>
                                    ))}
                                </ul>
                                :
                                <div className="d-flex flex-column justify-content-center align-items-center p-5">
                                    <i className="fa-solid fa-bell-slash fa-2x"></i>
                                    <h5 className="mt-3 fs-6">Your notifications live here</h5>
                                    <p className="text-muted text-center text-sm">Subscribe to your favorite channels to get
                                        notified about their latest videos.</p>
                                </div>
                    }
                    {
                        isFetchingNextPage &&
                        <div className={'d-flex justify-content-center align-items-center py-3'}>
                            <div class="spinner-border text-primary spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    }
                    {hasNextPage && <span ref={ref}></span>}
                </div>
            </Offcanvas>
        </>
    )
}

export function NotificationsList(props : Props) {

    const queryClient = new QueryClient({
        defaultOptions: {
            queries: {
                retry: false,
            }
        }
    });

    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props}/>
        </QueryClientProvider>
    )
}