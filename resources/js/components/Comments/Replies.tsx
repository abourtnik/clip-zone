import {useInfiniteQuery} from "@tanstack/react-query";
import {getCommentsReplies} from "@/api/clipzone";
import {Fragment} from "preact";
import Comment from "@/components/Comments/Comment";
import {CommentType} from "@/types";
import {useTranslation} from "react-i18next";
import {useEffect} from "preact/hooks";
import {show as showToast} from "@/functions/toast";
import {Loader} from "@/components/Commons";

type Props = {
    comment: CommentType,
    showReplies: boolean
}

export function Replies({comment, showReplies}: Props) {

    const { t } = useTranslation();

    const initialData = {
        pageParams: [1],
        pages: [{
            data: comment.replies?.data ?? [],
            next_page: comment.replies?.next_page,
            total: comment.replies?.total
        }]
    }

    const {
        data: replies,
        isFetchNextPageError,
        error,
        isFetching,
        fetchNextPage,
        hasNextPage,
    } = useInfiniteQuery({
        enabled : false,
        initialData: initialData,
        queryKey: ['comments', comment.id, 'replies'],
        queryFn: ({pageParam}) => getCommentsReplies(comment.video_uuid, comment.id, pageParam),
        initialPageParam: 1,
        getNextPageParam: (lastPage) => lastPage.next_page,
    });

    useEffect(() => {
        if (!isFetching && isFetchNextPageError) {
            showToast(error.message)
        }
    }, [isFetchNextPageError, isFetching]);

    return (
        <>
            {
                showReplies &&
                <div className={'mt-3'}>
                    <>
                        {replies.pages.map((group, i) => (
                            <Fragment key={i}>
                                {group.data.map((reply) => <Comment key={reply.id} comment={reply}/>)}
                            </Fragment>
                        ))}
                    </>
                    {
                        hasNextPage &&
                        <div className={'w-100 d-flex justify-content-end'}>
                            {
                                isFetching ?
                                    <div className={'d-flex gap-2 align-items-center text-primary text-sm fw-bold'}>
                                        <Loader small={true}/>
                                        <span>{t('Loading ...')}</span>
                                    </div> :
                                    <button
                                        type={'button'}
                                        className={'btn btn-sm text-primary fw-bold d-flex align-items-center gap-2'}
                                        onClick={() => fetchNextPage()}
                                    >
                                        <i className="fa-regular fa-plus"></i>
                                        <span>{t('Show more responses')}</span>
                                    </button>
                            }
                        </div>
                    }
                </div>
            }
        </>
    )
}
