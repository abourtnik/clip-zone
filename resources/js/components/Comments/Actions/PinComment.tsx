import {useTranslation} from "react-i18next";
import {CommentType} from "@/types";
import {useQueryClient} from "@tanstack/react-query";
import {useErrorMutation} from "@/hooks/useErrorMutation";
import {pinComment} from "@/api/clipzone";
import {Button, Modal} from "react-bootstrap";
import {useState} from "preact/hooks";

type Props = {
    comment: CommentType
}

export function PinComment ({comment} : Props) {

    const { t } = useTranslation()

    const queryClient = useQueryClient();

    const [confirm, setConfirm] = useState<boolean>(false);

    const {mutateAsync, mutate, isPending} = useErrorMutation({
        mutationFn: (type: 'pin' | 'unpin') => pinComment(type, comment.video_uuid, comment.id),
        mutationKey: ['comments.pin', comment.id],
        onSuccess: () => {
            queryClient.resetQueries({queryKey: ['comments', comment.video_uuid]})
        }
    });

    const type = comment.is_pinned ? 'unpin' : 'pin';

    const handlePin = async () => {
        await mutateAsync(type)
        setConfirm(false)
    }

    return (
        <>
            <li>
                <button onClick={() => comment.is_pinned ? mutate('unpin') : setConfirm(true)} className="dropdown-item d-flex align-items-center gap-3">
                    <i className="fa-solid fa-thumbtack"></i>
                    {comment.is_pinned ? t('Unpin') : t('Pin')}
                </button>
            </li>
            <Modal show={confirm} onHide={() => setConfirm(false)}>
                <Modal.Header closeButton>
                    <h5 className="modal-title">Pin this comment?</h5>
                </Modal.Header>
                <Modal.Body>
                    <div className="alert alert-info">
                        If you already pinned a comment, this will replace it
                    </div>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setConfirm(false)}>
                        Close
                    </Button>
                    <Button variant="primary" onClick={handlePin} disabled={isPending}>
                        {isPending && <span className="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>}
                        Pin
                    </Button>
                </Modal.Footer>
            </Modal>
        </>
    )
}
