import { useState } from 'preact/hooks';

export default function Description ({description}) {

    const [expand, setExpand] = useState(false);

    return (
        <div onClick={() => setExpand(expand => !expand)} className="my-4 card card-body text-decoration-none pointer-event" style={{'cursor': 'pointer', 'white-space': 'pre-line'}}>
            {expand ? description : description.slice(0, 250)}
            <div className="text-primary fw-bold mt-4">{expand ? 'Show less' : 'Show more'}</div>
        </div>
    )
}
