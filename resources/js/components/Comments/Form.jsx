import {useState} from "preact/hooks";
import Button from '../Button'
import { memo } from 'preact/compat';

const CommentForm = memo(({user, add, placeholder = 'Add a comment...', label = 'Comment'}) => {

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
                user ?
                    <form onSubmit={handleSubmit}>
                        <div className={'d-flex align-items-start gap-2'}>
                            <img className={'rounded-circle img-fluid'} src={user.avatar_url} alt={user.username + ' avatar'} style="width: 50px;"/>
                            <div className="mb-2 w-100">
                                <label htmlFor="content" className="form-label d-none"></label>
                                <textarea className="form-control" id="content" rows="4" name="content" placeholder={placeholder} required maxLength={5000}></textarea>
                            </div>
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
