import {VideoSkeleton} from "@/components/Skeletons/VideoSkeleton";
import {QueryClient, QueryClientProvider, useQuery, useQueryClient} from "@tanstack/react-query";
import {clearHistoryAll, getMyHistory} from '@/api/clipzone'
import HistoryDate from "@/components/History/HistoryDate";
import {useErrorMutation} from "@/hooks";
import {HistoryType} from "@/types";
import {produce} from "immer";
import {Button, Modal} from "react-bootstrap";
import {useState} from "preact/hooks";
import {useTranslation} from "react-i18next";

function Main () {

    const queryClient = useQueryClient();

    const [confirm, setConfirm] = useState<boolean>(false);

    const { t } = useTranslation();

    const {
        data: history,
        isLoading,
        isError,
        refetch,
    } = useQuery({
        queryKey: ['history'],
        queryFn: () => getMyHistory(),
    });

    const {
        mutateAsync,
        isPending
    } = useErrorMutation({
        mutationKey: ['history.clearAll'],
        mutationFn: () => clearHistoryAll(),
        onSuccess: () => {
            queryClient.setQueryData(['history'], (oldData: HistoryType | undefined) => {
                if (!oldData) return undefined;
                return produce(oldData, draft => {
                    draft.data = [];
                });
            });
        }
    });

    const handleDelete = async () => {
        await mutateAsync();
        setConfirm(false);
    }

    return (
        <>
            {
                isError &&
                <div class="row align-items-center h-75 mt-5 ">
                    <div class="col-10 offset-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                        <div class="row">
                            <div
                                class="col-6 d-none d-lg-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                                <i class="fa-solid fa-triangle-exclamation fa-10x"></i>
                            </div>
                            <div
                                class="col-12 col-lg-6 py-5 px-3 px-sm-5 d-flex align-items-center justify-content-center text-center">
                                <div>
                                    <h1 class="h3 mb-3 fw-normal">Something went wrong !</h1>
                                    <p class="text-muted">If the issue persists please contact us.</p>
                                    <button className="btn btn-primary rounded-5 text-uppercase" onClick={() => refetch()}>
                                        Try again
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            }
            <div>
                {
                    history && (
                        history.data.length > 0 ? (
                            <>
                                <div className={'d-flex justify-content-end p-2 mb-3'}>
                                    <button className="btn btn-danger btn-sm d-flex align-items-center gap-2" onClick={() => setConfirm(true)}>
                                        <i className="fa-solid fa-trash"></i>
                                        <span>{t('Clear watch history')}</span>
                                    </button>
                                </div>
                                {history.data.map((item, i) => (
                                    item.views.length > 0 && (
                                        <HistoryDate key={i} item={item}/>
                                    )
                                ))}
                            </>
                        ) : (
                            <div class="row align-items-center h-75 mt-5">
                                <div class="col-10 offset-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                                    <div class="row">
                                        <div class="col-6 d-none d-lg-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                                            <img class="img-fluid" src="https://www.clip-zone.com/images/pages/subscriptions.png" alt="Subscriptions"/>
                                        </div>
                                        <div class="col-12 col-lg-6 py-5 px-3 px-sm-5 d-flex align-items-center justify-content-center text-center">
                                            <div>
                                                <h1 class="h3 mb-3 fw-normal">{t('Your history is empty')}</h1>
                                                <p class="text-muted">{t('Watch videos and they will appear here')}</p>
                                                <a href="/" class="btn btn-outline-primary rounded-5 text-uppercase">
                                                    {t('See videos')}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            )
                    )
                }
                {
                    isLoading &&
                    <div className={'row mx-0 gx-3 gy-3 gy-sm-4 row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-3'}>
                        {[...Array(16).keys()].map(i => <VideoSkeleton key={i}/>)}
                    </div>
                }
            </div>
            <Modal show={confirm} onHide={() => setConfirm(false)}>
                <Modal.Header closeButton>
                    <h5 className="modal-title">{t('Clear watch history') + ' ?'}</h5>
                </Modal.Header>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setConfirm(false)}>
                        {t('Cancel')}
                    </Button>
                    <Button variant="danger" onClick={handleDelete} disabled={isPending}>
                        {isPending && <span className="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>}
                        {t('Delete')}
                    </Button>
                </Modal.Footer>
            </Modal>
        </>
    )
}

export function HistoryList() {

    const queryClient = new QueryClient({
        defaultOptions: {
            queries: {
                retry: false,
            }
        }
    });


    return (
        <QueryClientProvider client={queryClient}>
            <Main />
        </QueryClientProvider>
    )
}
