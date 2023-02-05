import {useState} from "preact/hooks";
import Button from './Button'

export default function ReplyForm ({setShowReply, comment, reply}) {

    const [loading, setLoading] = useState(false);

    const handleSubmit = async (event) => {

        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        setLoading(true);
        await reply(data)
        setLoading(false);
    }

    return (
        <form className={'mt-2'} onSubmit={handleSubmit}>
            <div className="mb-2">
                <label htmlFor={"content-" + comment.id} className="form-label d-none"></label>
                <textarea className="form-control" id={"content-" + comment.id} rows="3" name="content" placeholder="Add a reply ..." required maxLength={5000}></textarea>
            </div>
            <div className="d-flex justify-content-end gap-1">
                <button
                    onClick={() => setShowReply(false)}
                    className="btn btn-sm btn-secondary"
                    disabled={loading}
                >
                    Cancel
                </button>
                <Button type="submit" loading={loading}>
                    Reply
                </Button>
            </div>
        </form>
    )
}
