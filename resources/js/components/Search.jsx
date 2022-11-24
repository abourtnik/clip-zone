import { useState } from 'preact/hooks';

export default function Search ({}) {

    const [search, setSearch] = useState('');
    const [results, setResults] = useState([]);

    const handleChange = async (e) => {

        const value = e.target.value;

        setSearch(value);

        if (value) {
            const response = await fetch('/api/search?q=' + value, {
                headers: {
                    'Accept': 'application/json'
                },
            });

            const data = await response.json();

            setResults(data);

            console.log('res');
            console.log(results);

            console.log('data');
            console.log(data);
        } else {
            setResults([]);
        }
    }

    return (
        <>
        <form method="GET" className="d-flex w-100" role="search" action="/search">
            <div className="input-group">
                <input onChange={handleChange} className="form-control" type="search" placeholder="Search" aria-label="Search" name="q" value={search}/>
                <button className="btn btn-outline-secondary" type="submit">
                    {/*<i className="fa-solid fa-search"></i>*/}
                    Chercher
                </button>
            </div>
        </form>
        {
            results.length > 0 &&
                <div className={'position-absolute top-100 w-25 bg-white shadow p-3 border rounded'}>
                    <ul className={'list-unstyled'}>
                        {
                            results.map(result => (
                                <li>
                                    <a href="#" className="d-flex align-items-center gap-4 justify-content-start mb-2 text-decoration-none">
                                        <strong className={'text-muted'}>Video </strong>
                                        <div>{result}</div>
                                    </a>
                                </li>

                            ))
                        }
                        <li className={'text-center border-top pt-3'}>
                            <a className={''} href="/search">Voir les 50 resultats</a>
                        </li>
                    </ul>
                </div>
        }
        </>
    )
}
