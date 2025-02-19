import {Button} from '@/components/Commons'
import { memo } from 'preact/compat';
import {useTranslation} from "react-i18next";
import {useErrorMutation} from "@/hooks/useErrorMutation";
import {addComment} from "@/api/clipzone";
import {InfiniteData, useQueryClient} from "@tanstack/react-query";
import { CommentType, CommentDataSchema, CommentData, Paginator} from "@/types";
import {useRef} from "preact/hooks";
import {produce} from 'immer'

type Props = {
    video_id: string,
    placeholder?: string,
    label?:string
}

export const AddComment = memo(({video_id, placeholder = 'Add a comment...', label = 'Comment'} : Props) => {

    const queryClient = useQueryClient()

    const { t } = useTranslation();

    const textarea = useRef<HTMLTextAreaElement>(null);

    const {mutate, isPending} = useErrorMutation({
        mutationFn: (data: CommentData) => addComment(video_id, data),
        mutationKey: ['comments.add'],
        onSuccess: (comment) => {
            queryClient.setQueriesData({queryKey: ['comments', video_id]}, (oldData: InfiniteData<Paginator<CommentType>> | undefined) => {
                    if (!oldData) return undefined
                    return produce(oldData, draft => {
                        draft.pages[0].data.unshift(comment);
                        draft.pages[0].count = draft.pages[0].count! + 1;
                    });
                }
            )
            textarea.current!.value = '';
        }
    })

    const handleSubmit = async (event: any) => {

        event.preventDefault();

        const formData = new FormData(event.target);

        const data = Object.fromEntries(formData.entries());

        mutate(CommentDataSchema.parse(data))
    }

    return (
        <>
            {
                window.USER ?
                    <form onSubmit={handleSubmit}>
                        <div className={'d-flex align-items-start gap-2'}>
                            <img className={'rounded-circle img-fluid'} src={window.USER.avatar} alt={'user avatar'} style="width: 50px;"/>
                            <div className="mb-2 w-100">
                                <label htmlFor="message-content" className="form-label d-none"></label>
                                <textarea
                                    ref={textarea}
                                    className="form-control"
                                    id="message-content"
                                    rows={4}
                                    name="content"
                                    placeholder={t(placeholder)}
                                    required
                                    maxLength={5000}>
                                </textarea>
                            </div>
                        </div>
                        <div className="mb-3 d-flex justify-content-end">
                            <Button type={'submit'} loading={isPending} color={'success'}>
                                {t(label)}
                            </Button>
                        </div>
                    </form> :
                    <div className={'alert alert-primary d-flex justify-content-between align-items-center'}>
                        <span>{t('Sign in to write a comment')}</span>
                        <a href={'/login'} className={'btn btn-primary btn-sm'}>
                            {t('Sign in')}
                        </a>
                    </div>
            }
        </>
    )
});
