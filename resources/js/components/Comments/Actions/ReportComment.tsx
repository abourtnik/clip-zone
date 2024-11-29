import {useTranslation} from "react-i18next";
import {CommentType} from "@/types";
import {Modal, Button} from 'react-bootstrap';
import {useState} from "preact/hooks";
import {Report} from "@/components/Actions";


type Props = {
    comment: CommentType
}

export function ReportComment ({comment} : Props) {

    const { t } = useTranslation()

    const [open, setOpen] = useState<boolean>(false);

    return (
        <li>
            {
                window.USER ?
                    comment.can_report ?
                        <Report
                            buttonClass="dropdown-item d-flex align-items-center gap-3"
                            reportedClass={'dropdown-item d-flex align-items-center gap-2 mb-0 text-sm py-2'}
                            type={'comment'}
                            id={comment.id}
                        />
                        :
                        comment.reported_at &&
                        <div className="dropdown-item d-flex align-items-center gap-2 mb-0 text-sm py-2">
                            <i className="fa-solid fa-flag"></i>
                            <span>{t('Reported')} {comment.reported_at}</span>
                        </div>
                    :
                    <>
                        <Modal centered show={open} onHide={() => setOpen(false)}>
                            <Modal.Header closeButton>
                                <h5 className="modal-title">Do you want to report this content ?</h5>
                            </Modal.Header>
                            <Modal.Body>
                                <div className="alert alert-info">
                                    Log in to report content that violates our rules.
                                </div>
                            </Modal.Body>
                            <Modal.Footer>
                                <Button variant="secondary" onClick={() => setOpen(false)}>
                                    Close
                                </Button>
                                <Button variant="primary" as={'a'} href={'/login'}>
                                    Sign in
                                </Button>
                            </Modal.Footer>
                        </Modal>
                        <button className="dropdown-item d-flex align-items-center gap-3" onClick={() => setOpen(true) }>
                            <i className="fa-solid fa-flag"></i>
                            {t('Report')}
                        </button>
                    </>
            }
        </li>
    )
}
