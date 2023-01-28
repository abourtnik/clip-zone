import {useState, useEffect, useCallback} from 'preact/hooks';

export default function Button ({load= false, children, onClick}) {

    const [loading, setLoading] = useState(load);

    return (
        <button className="btn btn-success btn-sm" onClick={onClick}>
            {loading ? 'Loading ...' : children}
        </button>
    )
}
