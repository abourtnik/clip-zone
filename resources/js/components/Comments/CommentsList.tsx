import {QueryClient, QueryClientProvider, useInfiniteQuery} from "@tanstack/react-query";
import {useInView} from "react-intersection-observer";
import {getComments} from "@/api/clipzone";
import {useEffect, useState} from "preact/hooks";
import {Fragment} from "preact";
import {CommentSkeleton} from "@/components/Skeletons/CommentSkeleton";
import {CommentsSort} from "@/types";
import Comment from "@/components/Comments/Comment";
import {useTranslation} from "react-i18next";
import {AddComment} from "@/components/Comments/Actions";

type Props = {
    video: string,
    defaultSort: CommentsSort,
}

function Main ({video, defaultSort} : Props) {

    const { t } = useTranslation();

    const { ref, inView} = useInView();

    const [sort, setSort] = useState<CommentsSort>(defaultSort);

    const {
        data: comments,
        isLoading,
        isError,
        refetch,
        isFetching,
        fetchNextPage,
        hasNextPage,
    } = useInfiniteQuery({
        queryKey: ['comments', video, sort],
        queryFn: ({pageParam}) => getComments(video, pageParam, sort),
        initialPageParam: 1,
        gcTime: 0,
        getNextPageParam: (lastPage, allPages, lastPageParam) => {
            if (lastPage.meta.current_page * lastPage.meta.per_page  > lastPage.meta.to) {
                return undefined
            }
            return lastPageParam + 1
        }
    });

    useEffect( () => {
        if (inView && !isFetching && !isError) {
            fetchNextPage()
        }
    }, [inView]);

    const activeButton = (type: CommentsSort) => sort === type ? 'primary ' : 'outline-primary ';

    return (
        <div className="mb-4">
            <div className="mb-3 d-flex align-items-center justify-content-between">
                {
                    isLoading ? <div>{t('Loading ...')}</div> : <div>{t('Comments', {count: comments && comments.pages[0].count})}</div>
                }
                <div className={'d-flex gap-2 align-items-center'}>
                    <button onClick={() => setSort('top')} className={'btn btn-' + activeButton('top') + 'btn-sm'}>{t('Top Comments')}</button>
                    <button onClick={() => setSort('newest')} className={'btn btn-' + activeButton('newest') + 'btn-sm'}>{t('Newest first')}</button>
                </div>
            </div>
            <AddComment video_id={video}/>
            {
                isError &&
                <div className="d-flex border border-1 bg-light">
                    <div className="col-6 d-none d-lg-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                        <i className="fa-solid fa-triangle-exclamation fa-10x"></i>
                    </div>
                    <div className="col-12 col-lg-6 py-5 px-3 px-sm-5 d-flex align-items-center justify-content-center text-center">
                        <div>
                            <h1 className="h3 mb-3 fw-normal">Something went wrong !</h1>
                            <p className="text-muted">If the issue persists please contact us.</p>
                            <button className="btn btn-primary rounded-5 text-uppercase" onClick={refetch}>
                                Try again
                            </button>
                        </div>
                    </div>
                </div>
            }
            <div>
                {
                    comments &&
                    <>
                        {comments.pages.map((group, i) => (
                            <Fragment key={i}>
                                {group.data.map((comment) => <Comment key={comment.id} comment={comment}/>)}
                            </Fragment>
                        ))}
                    </>
                }
                {
                    (isFetching || isLoading) &&
                    [...Array(4).keys()].map(i => <CommentSkeleton key={i}/>)
                }
                {hasNextPage && <span ref={ref}></span>}
            </div>
        </div>
    )
}

export function CommentsList(props: Props) {

    const queryClient = new QueryClient({
        defaultOptions: {
            queries: {
                retry: false,
            }
        }
    });


    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props} />
        </QueryClientProvider>
    )
}
