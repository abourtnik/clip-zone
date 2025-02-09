import {Button} from '@/components/commons'
import { memo } from 'preact/compat';
import {Expand} from "../ExpandComment";
import {useTranslation} from "react-i18next";
import {CommentType, CommentData, CommentDataSchema, Paginator} from "@/types";
import {useErrorMutation} from "@/hooks/useErrorMutation";
import {updateComment} from "@/api/clipzone";
import {InfiniteData, useQueryClient} from "@tanstack/react-query";
import {produce} from 'immer'

type Props = {
    comment: CommentType,
    edit: boolean
    setEdit: (v:boolean) => void,
}

export const UpdateComment = memo(({comment, edit, setEdit} : Props) => {

    const { t } = useTranslation();

    const queryClient = useQueryClient()

    const {mutateAsync, isPending} = useErrorMutation({
        mutationFn: (data: CommentData) => updateComment(comment.video_uuid, comment.id, data),
        mutationKey: ['comments.update', comment.id],
        onSuccess: (updatedComment) => {

            const queryKey = comment.is_reply ? ['comments', updatedComment.parent_id, 'replies'] : ['comments', comment.video_uuid];

            queryClient.setQueriesData({queryKey: queryKey}, (oldData: InfiniteData<Paginator<CommentType>> | undefined) => {
                if (!oldData) return undefined;
                return produce(oldData, draft => {
                    draft.pages.forEach(page => {
                        page.data = page.data.map(c => c.id === updatedComment.id ? updatedComment : c);
                    });
                });
            })
            setEdit(false)
        }
    })

    const handleSubmit = async (event: any) => {

        event.preventDefault();

        const formData = new FormData(event.target);

        const data = Object.fromEntries(formData.entries());

        await mutateAsync(CommentDataSchema.parse(data))
    }

    return (
        <>
            {
                edit ?
                    <form className={'my-3'} onSubmit={handleSubmit}>
                        <textarea className="form-control" rows={3} name="content" required>{comment.content}</textarea>
                        <div className={'d-flex gap-1 mt-2'}>
                            <Button type={'submit'} loading={isPending}>
                                {t('Save')}
                            </Button>
                            <button
                                type={'button'}
                                disabled={isPending}
                                onClick={() => setEdit(false)}
                                className={'btn btn-secondary btn-sm'}
                            >
                                {t('Cancel')}
                            </button>
                        </div>
                    </form>
                    : <Expand comment={comment}/>
            }
        </>
    )
});
