import { useState, useCallback, useRef } from 'preact/hooks';
import {debounce} from "../functions";
import {jsonFetch, useClickOutside} from '../hooks'

export default function SearchModel ({endpoint, name, label = null, value = null}) {

    const defaultValue = JSON.parse(value);

    const ref = useRef(null);
    const inputRef = useRef(null);

    const [search, setSearch] = useState(defaultValue?.label ?? '');
    const [results, setResults] = useState([]);
    const [loading, setLoading] = useState(false);
    const [showResults, setShowResults] = useState(false);
    const [selectedIndex, setSelectedIndex] = useState(null);

    useClickOutside(ref, () => setShowResults(false))

    const handleChange = e => {
        const value = e.target.value;
        setSelectedIndex(null);
        setLoading(true);
        setSearch(value);
        suggest(value)
        inputRef.current.value = null
    };

    const handleKeys = (e) => {
        switch (e.key) {
            case 'ArrowDown':
                setSelectedIndex(i => {

                    if (!results.length){
                        return null;
                    }

                    if(i === null) {
                        return 0
                    }
                    else if (results.length === i) {
                        return null
                    }
                    else {
                        return  i + 1
                    }
                })
                break;
            case 'ArrowUp':
                setSelectedIndex(i => {

                    if (!results.length){
                        return null;
                    }

                    if(i === null) {
                        return results.length
                    }
                    else if (i === 0) {
                        return null
                    }
                    else {
                        return  i - 1
                    }
                })
                break;
            case 'Enter':
                e.preventDefault();
                onSelect(results[selectedIndex])
                break;
        }
    }

    const suggest = useCallback(debounce(async value => {
        jsonFetch(`${endpoint}?q=${value}`).then(results => {
            setResults(results.data);
        }).catch(e => e).finally(() => setLoading(false));
    }, 300), []);

    const onSelect = (result) => {
        setSearch(result.label)
        inputRef.current.value = result.value
        setShowResults(false)
    }

    return (
        <div className={'position-relative'} ref={ref}>
            <label htmlFor={name} className="form-label fw-bold">{label ?? name.charAt(0).toUpperCase() + name.slice(1)}</label>
            <div className={'position-relative'}>
                <input
                    onClick={() => setShowResults(true)}
                    onChange={handleChange}
                    type="search"
                    className="form-control"
                    id={name}
                    placeholder={'Search ' + name + ' ...'}
                    onKeyDown={handleKeys}
                    value={search}
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
            {
                (showResults && search.trim() && !loading) &&
                <div className={'position-absolute w-100 rounded-2 bg-white shadow-lg border border-1'} style={{top:'75px'}}>
                    {
                        results.length ?
                            <ul className={'list-unstyled mb-0'}>
                                {
                                    results.map((result, key) => (
                                        <li className={'text-black px-3 py-2 hover-primary cursor-pointer ' + (selectedIndex === key ? 'selected' : null)} onClick={() => onSelect(result)} key={key}>
                                            {result.label}
                                        </li>
                                    ))
                                }
                            </ul>
                            :
                            <div className={'d-flex justify-content-center align-items-center flex-column py-3'}>
                                <strong>No results found</strong>
                            </div>
                    }
                </div>
            }
            <input type="hidden" name={name} ref={inputRef} defaultValue={defaultValue?.value}/>
        </div>

    )
}
