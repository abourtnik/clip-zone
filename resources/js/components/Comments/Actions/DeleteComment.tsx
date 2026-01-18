import {useTranslation} from "react-i18next";
import {CommentType, Paginator} from "@/types";
import {InfiniteData, useQueryClient} from "@tanstack/react-query";
import {useErrorMutation} from "@/hooks/useErrorMutation";
import {deleteComment} from "@/api/clipzone";
import {Modal, Button} from 'react-bootstrap';
import {useState} from "preact/hooks";
import {produce} from "immer";

type Props = {
    comment: CommentType
}

export function DeleteComment ({comment} : Props) {

    const { t } = useTranslation()

    const queryClient = useQueryClient();

    const [confirm, setConfirm] = useState<boolean>(false);

    const {mutateAsync, isPending} = useErrorMutation({
        mutationFn: () => deleteComment(comment.video_uuid, comment.id),
        mutationKey: ['comments.delete', comment.id],
        onSuccess: () => {

            const queryKey = comment.is_reply ? ['comments', comment.parent_id, 'replies'] : ['comments', comment.video_uuid];

            queryClient.setQueriesData({queryKey: queryKey}, (oldData: InfiniteData<Paginator<CommentType>> | undefined) => {
                if (!oldData) return undefined;
                return produce(oldData, draft => {
                    draft.pages.forEach(page => {
                        page.data = page.data.filter(c => c.id !== comment.id);
                        page.count = page.count! - (comment.is_reply ? 1 : comment.replies_count! + 1);
                    });
                });
            })

            if (comment.is_reply) {
                queryClient.setQueriesData({queryKey: ['comments', comment.video_uuid]}, (oldData: InfiniteData<Paginator<CommentType>> | undefined) => {
                    if(!oldData) return undefined;
                    return produce(oldData, draft => {
                        draft.pages[0].count = draft.pages[0].count! - 1;
                        draft.pages.forEach(page => {
                            page.data.forEach(c => {
                                if (c.id === comment.parent_id) {
                                    c.has_replies = c.replies_count! > 1;
                                    c.replies_count = c.replies_count! - 1;
                                }
                            });
                        });
                    });
                })
            }
        }
    })

    const handleDelete = async () => {
        await mutateAsync()
        setConfirm(false)
    }

    return (
        <>
            <li>
                <button type={'button'} className="dropdown-item d-flex align-items-center gap-3" onClick={() => setConfirm(true)}>
                    <i className="fa-solid fa-trash-can"></i>
                    {t('Delete')}
                </button>
            </li>
            <Modal show={confirm} onHide={() => setConfirm(false)}>
                <Modal.Header closeButton>
                    <h5 className="modal-title">{t('Permanently delete comment ?')}</h5>
                </Modal.Header>
                <Modal.Body>
                    <div className="alert alert-danger">
                        <span>{t('Delete')}</span>
                        {
                            comment.user.id === window.USER!.id ? <span>{t('your')}</span> : <span className={'fw-bold'}>{comment.user.username}</span>
                        }
                        <span>comment permanently ?</span>
                    </div>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setConfirm(false)}>
                        {t('Close')}
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
