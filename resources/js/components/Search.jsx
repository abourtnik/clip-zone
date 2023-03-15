import { useState, useCallback, useRef } from 'preact/hooks';
import {debounce} from "../functions";
import {Search as SearchIcon} from  './Icon'
import {useClickOutside} from '../hooks'

export default function Search ({query = '', responsive = true}) {

    const ref = useRef(null)

    const [search, setSearch] = useState(query);
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(false);
    const [showResults, setShowResults] = useState(false);

    useClickOutside(ref, () => setShowResults(false))

    const handleChange = e => {
        const value = e.target.value;
        setShowResults(true)
        setLoading(true);
        setSearch(value);
        suggest(value)
    };

    const suggest = useCallback(debounce(async value => {

        const response = await fetch('/api/search?q=' + value, {
            headers: {
                'Accept': 'application/json'
            },
        });

        const data = await response.json();
        setData(data);
        setLoading(false)

    }, 300), []);

    const width = responsive ? 'w-100 start-0' : 'w-35 rounded-4';
    const textSize = responsive ? 'text-sm' : '';

    return (
        <>
        <form ref={ref} method="GET" className="d-flex w-100" role="search" action="/search">
            <div className="input-group">
                <div className={'position-relative'} style={{flex: '1 1 auto'}}>
                    <input onClick={() => setShowResults(true)} onChange={handleChange} className="form-control rounded-5 rounded-end radius-end-0" type="search" placeholder="Search" aria-label="Search" name="q" value={search}/>
                    {
                        (loading && search.trim()) &&
                        <div className="position-absolute top-50 right-0 translate-middle" >
                            <div className={'spinner-border spinner-border-sm'} role="status">
                                <span className="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    }
                </div>
                <button className="btn btn-outline-secondary rounded-5 rounded-start radius-start-0 px-4" type="submit">
                    <SearchIcon/>
                </button>
            </div>
        </form>
        {
            (showResults && search.trim() && !loading) &&
                <div className={'position-absolute ' + width + ' bg-white shadow-lg border border-1 pt-3'} style={{top:'53px'}}>
                    {
                        data.total ?
                            <ul className={'list-unstyled mb-0'}>
                                {
                                    data.items.map(result => (
                                        <li>
                                            <a href={result.url}
                                               className="d-flex align-items-center gap-2 justify-content-start text-decoration-none px-3 py-2 result">

                                                <div className={'pe-2'}>
                                                    <SearchIcon/>
                                                </div>
                                                <div className={'text-black text-lowercase ' + textSize}>{result.title}</div>
                                            </a>
                                        </li>
                                    ))
                                }
                                <li className={'text-center border-top d-flex align-items-center'}>
                                    <a className={'text-decoration-none text-muted px-2 pt-2 w-100 text-sm fw-bold result py-2'} href={data.route}>
                                        See {data.total} result{data.total > 1 && 's'}
                                    </a>
                                </li>
                            </ul>
                            :
                            <div className={'d-flex justify-content-center align-items-center flex-column pb-3'}>
                                <strong>No results found</strong>
                            </div>
                    }
                </div>
        }
        </>
    )
}
