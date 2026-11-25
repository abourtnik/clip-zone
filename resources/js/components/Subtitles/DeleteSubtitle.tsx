import {useTranslation} from "react-i18next";
import {SubtitleType} from "@/types";
import {useQueryClient} from "@tanstack/react-query";
import {useErrorMutation} from "@/hooks/useErrorMutation";
import {deleteSubtitle} from "@/api/clipzone";
import {Modal, Button} from 'react-bootstrap';
import {useState} from "preact/hooks";
import {produce} from "immer";

type Props = {
    subtitle: SubtitleType
}

export function DeleteSubtitle ({subtitle} : Props) {

    const { t } = useTranslation()

    const queryClient = useQueryClient();

    const [confirm, setConfirm] = useState<boolean>(false);

    const {mutateAsync, isPending} = useErrorMutation({
        mutationFn: () => deleteSubtitle(subtitle.video_id, subtitle.id),
        mutationKey: ['subtitle.delete', subtitle.id],
        onSuccess: () => queryClient.invalidateQueries({ queryKey: ['videos', subtitle.video_id, 'subtitles'] })
    })

    const handleDelete = async () => {
        await mutateAsync()
        setConfirm(false)
    }

    return (
        <>
            <button type={'button'} class="btn btn-danger btn-sm" title="Delete subtitle" onClick={() => setConfirm(true)}>
                <i class="fa-solid fa-trash"></i>
            </button>
            <Modal show={confirm} onHide={() => setConfirm(false)}>
                <Modal.Header closeButton>
                    <h5 className="modal-title">{t('Permanently delete {name} subtitle ?', {name: subtitle.name})}</h5>
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
