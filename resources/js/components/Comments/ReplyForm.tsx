import {Button} from '@/components/commons'
import {useTranslation} from "react-i18next";
import {CommentData, CommentType, CommentDataSchema, ResponsesPaginator, Paginator} from "@/types";
import {useErrorMutation} from "@/hooks/useErrorMutation";
import {addComment} from "@/api/clipzone";
import {InfiniteData, useQueryClient} from "@tanstack/react-query";
import {useRef} from "preact/hooks";
import {produce} from "immer";

type Props = {
    comment: CommentType,
    reply: () => void,
    setShowReplyForm: (v: boolean) => void,
}

export function ReplyForm ({comment, reply, setShowReplyForm} : Props) {

    const { t } = useTranslation();

    const textarea = useRef<HTMLTextAreaElement>(null);

    const queryClient = useQueryClient()

    const {mutateAsync, isPending} = useErrorMutation({
        mutationFn: (data: CommentData) => addComment(comment.video_uuid, data),
        mutationKey: ['comments.reply', comment.id],
        onSuccess: (newReply) => {

            queryClient.setQueriesData({queryKey: ['comments', comment.video_uuid]}, (oldData: InfiniteData<Paginator<CommentType>> | undefined) => {
                if(!oldData) return undefined;
                return produce(oldData, draft => {
                    draft.pages[0].count = draft.pages[0].count! + 1
                    draft.pages.forEach(page => {
                        page.data.forEach(c => {
                            if (c.id === comment.id) {
                                c.has_replies = true;
                                c.replies_count = c.replies_count! + 1;

                                /*

                                c.is_video_author_reply = newReply.user.is_video_author

                                if (newReply.user.is_video_author) {
                                    c.is_video_author_reply = true;
                                    c.video_author = newReply.user;
                                }

                               */
                            }
                        });
                    });
                });
            })

            queryClient.setQueryData(['comments', comment.id, 'replies'], (oldData: InfiniteData<ResponsesPaginator> | undefined) => {
                if(!oldData) return undefined;
                return produce(oldData, draft => {
                    draft.pages[0].data.unshift(newReply);
                });
            })

            textarea.current!.value = '';
            reply()
        }
    })

    const handleSubmit = async (event: any) => {

        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        await mutateAsync(CommentDataSchema.parse(data))
    }

    return (
        <form className={'mt-3'} onSubmit={handleSubmit}>
            <div className={'d-flex align-items-start gap-2'}>
                <img className={'rounded-circle img-fluid'} src={window.USER?.avatar} alt={'user avatar'} style="width: 35px;"/>
                <div className="mb-2 w-100">
                    <label htmlFor={"reply-" + comment.id} className="form-label d-none"></label>
                    <textarea
                        ref={textarea}
                        className="form-control"
                        id={"reply-" + comment.id}
                        rows="3"
                        name="content"
                        placeholder={t('Add a reply ...')}
                        required
                        maxLength={5000}>
                    </textarea>
                </div>
                <input type="hidden" name={'parent_id'} value={comment.id} />
            </div>
            <div className="d-flex justify-content-end gap-1">
                <Button type="submit" loading={isPending}>
                    {t('Reply')}
                </Button>
                <button
                    type="button"
                    onClick={() => setShowReplyForm(false)}
                    className="btn btn-sm btn-secondary"
                    disabled={isPending}
                >
                    {t('Cancel')}
                </button>
            </div>
        </form>
    )
}
