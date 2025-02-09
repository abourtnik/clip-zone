import {Modal} from "react-bootstrap";
import {useState} from "preact/hooks";
import {REPORT_REASONS, ReportData, ReportDataSchema} from "@/types";
import {useErrorMutation} from "@/hooks";
import {report} from "@/api/clipzone";
import {Button} from "@/components/commons";
import {Fragment} from "preact";
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";

type Props = {
    buttonClass: string,
    reportedClass: string,
    type: string,
    id: string | number,
}

export function Main ({buttonClass, reportedClass, type, id} : Props)  {

    const [open, setOpen] = useState<boolean>(false);
    const [count, setCount] = useState<number>(0);
    const [reported, setReported] = useState<boolean>(false);

    const {mutate, isPending} = useErrorMutation({
        mutationFn: (data: ReportData) => report(data),
        mutationKey: ['report', type, id],
        onSuccess: () => {
            setReported(true);
        }
    })

    const handleSubmit = (event: any) => {

        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        mutate(ReportDataSchema.parse(data))
    }

    return (
        <>
            {
                !reported &&
                <button className={buttonClass} onClick={() => setOpen(true)}>
                    <i class="fa-regular fa-flag"></i>&nbsp;Report
                </button>
            }
            {
                reported &&
                <div class={reportedClass}>
                    <i class="fa-regular fa-flag"></i>
                   <span>Reported a few seconds ago</span>
                </div>
            }
            <Modal size={'lg'} show={open} onHide={() => setOpen(false)} animation={true}>
                <Modal.Header closeButton>
                    <h5 className="modal-title">Report {type}</h5>
                </Modal.Header>
                <Modal.Body className="ms-3 mt-2">
                    {
                        reported &&
                        <div className={'text-center'}>
                            <img className="img-fluid" src="/images/reports/ok.jpg" alt="Report success"/>
                            <h3 className="my-4">Thanks for reporting</h3>
                            <p className="text-muted mt-4">
                                If we find this content to be in violation of our Community Guidelines, we will remove
                                it.
                            </p>
                        </div>
                    }
                    {
                        !reported &&
                        <form onSubmit={handleSubmit} id="report-form">
                            {
                                REPORT_REASONS.map((reason, index) => (
                                    <div className="form-check mb-3">
                                        <input className="form-check-input" type="radio" name="reason" id={'reason-' + index} value={reason} required/>
                                        <label className="form-check-label" htmlFor={'reason-' + index}>
                                            {reason}
                                        </label>
                                    </div>
                                ))
                            }
                            <hr/>
                            <div className="mb-3">
                                <label htmlFor="comment" className="form-label">Provide additional details</label>
                                <textarea
                                    className="form-control"
                                    id="comment"
                                    rows={3}
                                    name="comment"
                                    onChange={(e: any) => setCount(e.currentTarget.value.length)}
                                    maxLength={5000}
                                ></textarea>
                                <div class="form-text">
                                    {count} / 5000
                                </div>
                            </div>
                            <input type="hidden" name="id" value={id}/>
                            <input type="hidden" name="type" value={'App\\Models\\' + type.charAt(0).toUpperCase() + type.slice(1)}/>
                            <p className="text-muted text-sm mt-4">
                                Reported videos, comments and users are reviewed by staff 24 hours a day,
                                7 days a week to determine whether they violate Community Guidelines.
                                You can follow the progress of your reports from your account : <a target="_blank" className="text-decoration-none" href="/profile/reports">See my reports</a>
                            </p>
                        </form>
                    }
                </Modal.Body>
                <Modal.Footer>
                    {
                        !reported &&
                        <Fragment>
                            <button type="button" className="btn btn-secondary" onClick={() => setOpen(false)}>Cancel</button>
                            <Button small={false} form={'report-form'} type="submit" loading={isPending} color={'primary'}>
                                Report
                            </Button>
                        </Fragment>
                    }
                    {
                        reported &&
                        <button type="button" className="btn btn-secondary" onClick={() => setOpen(false)}>Close</button>
                    }

                </Modal.Footer>
            </Modal>
        </>
    )
}

export function Report(props: Props) {

    const queryClient = new QueryClient({
        defaultOptions: {
            queries: {
                retry: false,
            }
        }
    });

    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props}/>
        </QueryClientProvider>
    )
}
