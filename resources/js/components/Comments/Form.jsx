import {useState} from "preact/hooks";
import Button from '../Button'
import { memo } from 'preact/compat';

const CommentForm = memo(({auth, add, placeholder = 'Add a comment...', label = 'Comment'}) => {

    const [loading, setLoading] = useState(false);

    const handleSubmit = async (event) => {

        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        setLoading(true);
        await add(data)
        setLoading(false);
    }

    return (
        <>
            {
                auth ?
                    <form onSubmit={handleSubmit}>
                        <div className="mb-2">
                            <label htmlFor="content" className="form-label d-none"></label>
                            <textarea className="form-control" id="content" rows="4" name="content" placeholder={placeholder} required maxLength={5000}></textarea>
                        </div>
                        <div className="mb-3 d-flex justify-content-end">
                            <Button type={'submit'} loading={loading} color={'success'}>
                                {label}
                            </Button>
                        </div>
                    </form> :
                    <div className={'alert alert-primary d-flex justify-content-between align-items-center'}>
                        <span>Sign in to write a comment</span>
                        <a href={'/login'} className={'btn btn-primary btn-sm'}>
                            Sign in
                        </a>
                    </div>
            }
        </>
    )
});

export default CommentForm
