import {useState} from "preact/hooks";
import { memo } from 'preact/compat';
import {Button} from '../commons'

const Form = memo(({create}) => {

    const [show, setShow] = useState(false);
    const [loading, setLoading] = useState(false);
    const [count, setCount] = useState(0);

    const handleSubmit = async (event) => {

        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        setLoading(true);
        await create(data)
        setLoading(false);
        setShow(false);
        document.getElementById('title').value = '';
        document.getElementById('status').value = '';
        setCount(0);
    }

    const handleChange = (event) => {
        const value = event.target.value;
        setCount(value.length)
    }

    return (
        <>
            {
                !show &&
                <button onClick={() => setShow(true)} className="btn btn-success btn-sm">
                    <i className="fa-solid fa-plus"></i>&nbsp;
                    Create new playlist
                </button>
            }
            {
                show &&
                <form onSubmit={handleSubmit} className="card card-body bg-light">
                    <div className="mb-3">
                        <label htmlFor="title" className="form-label">Title</label>
                        <input
                            type="text"
                            className="form-control"
                            id="title"
                            name="title"
                            required
                            maxLength="150"
                            onChange={handleChange}
                        />
                        <div className="form-text">
                            {count} / 150
                        </div>
                    </div>
                    <div className="mb-3">
                        <label htmlFor="status" className="form-label">Visibility</label>
                        <select className="form-control" name="status" id="status" required>
                            <option selected="" value="0">Public</option>
                            <option value="1">Private</option>
                            <option value="2">Unlisted</option>
                        </select>
                    </div>
                    <div className="d-flex gap-2 justify-content-end">
                        <button onClick={() => setShow(false)} type="button" className="btn btn-secondary btn-sm">Cancel</button>
                        <Button type={'submit'} loading={loading} color={'success'}>
                            Create
                        </Button>
                    </div>
                </form>
            }
        </>
    )
});

export default Form
