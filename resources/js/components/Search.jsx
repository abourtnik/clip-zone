import { useState, useCallback } from 'preact/hooks';
import {debounce} from "../functions";

export default function Search ({}) {

    const [search, setSearch] = useState('');
    const [data, setData] = useState({
        total: 0,
        items: []
    });
    const [loading, setLoading] = useState(false);

    let timeout = 0;

    const handleChange = debounce(async (e) => {
        const value = e.target.value;
        setSearch(value);
        const response = await fetch('/api/search?q=' + value, {
            headers: {
                'Accept': 'application/json'
            },
        });
        const data = await response.json();
        setData(data);
        setLoading(false)

    }, 300)

    const changeHandler = event => {

        console.log('changeHandler')

        const value = event.target.value;

        //setLoading(true);

        if(timeout) clearTimeout(timeout);



        timeout = setTimeout(async () => {

            setSearch(value);

            const response = await fetch('/api/search?q=' + value, {
                headers: {
                    'Accept': 'application/json'
                },
            });

            const data = await response.json();
            setData(data);
            setLoading(false)

        }, 300)


    };
    //const debouncedChangeHandler = debounce(changeHandler, 300)

    return (
        <>
        <form method="GET" className="d-flex w-100" role="search" action="/search">
            <div className="input-group">
                <input onInput={changeHandler} className="form-control" type="search" placeholder="Search" aria-label="Search" name="q" value={search}/>
                <button className="btn btn-outline-secondary" type="submit">
                    {/*<i className="fa-solid fa-search"></i>*/}
                    Chercher
                </button>
            </div>
        </form>
        {
            (search.trim() && !loading) &&
                <div className={'position-absolute w-25 bg-white shadow border rounded'} style={{top:'47px'}}>
                    {
                        data.total ?
                            <ul className={'list-unstyled mb-0'}>
                                {
                                    data.items.map(result => (
                                        <li>
                                            <a href={result.url}
                                               className="d-flex align-items-center gap-2 justify-content-start text-decoration-none px-4 py-2 result">
                                                <div className={'text-muted border-end pe-2'}>{result.category}</div>
                                                <div className={'text-black'}>{result.title}</div>
                                            </a>
                                        </li>

                                    ))
                                }
                                <li className={'text-center border-top d-flex'}>
                                    <a className={'text-decoration-none text-muted px-2 pt-2 w-100 text-sm fw-bold result py-2'} href={data.route}>
                                        Voir les {data.total} resultats
                                    </a>
                                </li>
                            </ul>
                            :
                            <div className={'d-flex justify-content-center align-items-center flex-column py-3'}>
                                {/*<i className="fa-solid fa-database fa-2x my-3"></i>*/}
                                <strong>Aucun resultat trouvé</strong>
                            </div>
                    }
                </div>
        }
        </>
    )
}
