import { useState, useCallback } from 'preact/hooks';
import {debounce} from "../functions";
import {Search as SearchIcon} from  './Icon'

export default function Search ({query = ''}) {

    const [search, setSearch] = useState(query);
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(true);

    const handleChange = e => {
        const value = e.target.value;
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

    return (
        <>
        <form method="GET" className="d-flex w-100" role="search" action="/search">
            <div className="input-group">
                <input onChange={handleChange} className="form-control rounded-5 rounded-end radius-end-0" type="search" placeholder="Search" aria-label="Search" name="q" value={search}/>
                <button className="btn btn-outline-secondary rounded-5 rounded-start radius-start-0 px-4" type="submit">
                    <SearchIcon/>
                </button>
            </div>
        </form>
        {
            (search.trim() && !loading) &&
                <div className={'position-absolute w-30 bg-white shadow-lg border border-1 rounded-4 pt-3'} style={{top:'49px'}}>
                    {
                        data.total ?
                            <ul className={'list-unstyled mb-0'}>
                                {
                                    data.items.map(result => (
                                        <li>
                                            <a href={result.url}
                                               className="d-flex align-items-center gap-2 justify-content-start text-decoration-none px-3 py-2 result">
                                                <div className={'text-muted border-end pe-2'}>{result.category}</div>
                                                <div className={'text-black'}>{result.title}</div>
                                            </a>
                                        </li>

                                    ))
                                }
                                <li className={'text-center border-top d-flex'}>
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
