import { useState, useCallback, useRef } from 'preact/hooks';
import {debounce} from "../functions";
import {jsonFetch, useClickOutside} from '../hooks'

export default function Search ({query = '', responsive = true}) {

    const ref = useRef(null)

    const [search, setSearch] = useState(query);
    const [results, setResults] = useState([]);
    const [loading, setLoading] = useState(false);
    const [showResults, setShowResults] = useState(false);
    const [selectedIndex, setSelectedIndex] = useState(null);

    useClickOutside(ref, () => setShowResults(false))

    const handleChange = e => {
        const value = e.target.value;
        setLoading(true);
        setSearch(value);
        suggest(value)
    };

    const handleKeys = (e) => {
        switch (e.key) {
            case 'ArrowDown':
                setSelectedIndex(i => {

                    if (!results.total){
                        return null;
                    }

                    if(i === null) {
                        return 0
                    }
                    else if (results.total === i) {
                        return null
                    }
                    else {
                        return  i + 1
                    }
                })
                break;
            case 'ArrowUp':
                setSelectedIndex(i => {

                    if (!results.total){
                        return null;
                    }

                    if(i === null) {
                        return results.total
                    }
                    else if (i === 0) {
                        return null
                    }
                    else {
                        return  i - 1
                    }
                })
                break;
        }
    }

    const handleSubmit = (e) => {
        if (selectedIndex && selectedIndex !== results.total) {
            e.preventDefault()
            window.location.href = results.items[selectedIndex].url;
        }
    }

    const suggest = useCallback(debounce(async value => {
        jsonFetch(`/api/search?q=${value}`).then(results => {
            setResults(results);
        }).catch(e => e).finally(() => setLoading(false));
    }, 300), []);

    const width = responsive ? 'w-100 start-0' : 'w-35 rounded-4';
    const textSize = responsive ? 'text-sm' : '';

    return (
        <>
        <form ref={ref} method="GET" className="d-flex w-100 mb-0" role="search" action="/search" onSubmit={handleSubmit}>
            <div className="input-group flex-nowrap">
                <div className={'position-relative'} style={{flex: '1 1 auto'}}>
                    <input
                        onClick={() => setShowResults(true)}
                        onChange={handleChange}
                        className="form-control rounded-5 rounded-end radius-end-0 border border-secondary"
                        type="search"
                        placeholder="Search"
                        aria-label="Search"
                        name="q"
                        value={search}
                        onKeyDown={handleKeys}
                    />
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
                    <i className="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
        {
            (showResults && search.trim() && !loading) &&
                <div className={'position-absolute ' + width + ' bg-white shadow-lg border border-1 pt-3 rounded-top rounded-bottom'} style={{top:'53px'}}>
                    {
                        results.total ?
                            <ul className={'list-unstyled mb-0'}>
                                {
                                    results.items.map((result, key) => (
                                        <li key={key}>
                                            <a
                                                href={result.url}
                                                className={"d-flex align-items-center gap-2 justify-content-start text-decoration-none px-3 py-2 hover-primary " + (selectedIndex === key ? 'selected' : null)}
                                            >
                                                <div className={'pe-2'}>
                                                    <i className="fa-solid fa-magnifying-glass"></i>
                                                </div>
                                                <div className={'text-black text-lowercase ' + textSize}>{result.title}</div>
                                            </a>
                                        </li>
                                    ))
                                }
                                <li className={'text-center border-top d-flex align-items-center rounded-bottom'}>
                                    <a className={"text-decoration-none text-muted px-2 pt-2 w-100 text-sm fw-bold hover-primary py-2 rounded-bottom " + (selectedIndex === results.total ? 'selected' : null)} href={results.route}>
                                        See {results.total} result{results.total > 1 && 's'}
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
