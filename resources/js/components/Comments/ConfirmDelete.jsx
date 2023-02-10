import { useState } from 'preact/hooks';
import {Trash} from "../Icon";

export default function ConfirmDelete ({onDelete, comment}) {

    const [confirmDelete, setConfirmDelete] = useState(false);

    return (
        <li>
            {
                confirmDelete ?
                    <div className="dropdown-item d-flex gap-3">
                        <button className={'d-flex align-items-center gap-2 text-danger p-0 bg-transparent'} onClick={() => onDelete(comment)}>
                            <Trash />
                            Confirm delete ?
                        </button>
                        <button className={'text-primary fw-bold bg-transparent'} onClick={() => setConfirmDelete(false)}>Cancel</button>
                    </div>
                    :
                    <button className="dropdown-item d-flex align-items-center gap-3" onClick={() => setConfirmDelete(true)}>
                        <Trash />
                        Remove
                    </button>
            }
        </li>
    )
}
