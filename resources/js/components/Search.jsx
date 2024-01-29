import { useState, useCallback, useRef } from 'preact/hooks';
import {debounce} from "../functions";
import {jsonFetch, useClickOutside} from '../hooks'
import {useTranslation} from "react-i18next";
import clsx from 'clsx';

export default function Search ({query = '', responsive = true}) {

    const { t } = useTranslation();

    const ref = useRef(null)

    const [search, setSearch] = useState(query);
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
        if (value.trim()) {
            suggest(value)
        }
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
                    else if (results.items.length === i) {
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
                        return results.items.length
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
        if (selectedIndex !== null && selectedIndex !== results.items.length) {
            e.preventDefault()
            window.location.href = results.items[selectedIndex].url;
        }
    }

    const suggest = useCallback(debounce(async value => {
        jsonFetch(`/api/search?q=${value}`).then(results => {
            setResults(results);
        }).catch(e => e).finally(() => setLoading(false));
    }, 300), []);

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
                        placeholder={t("Search")}
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
            (showResults && search.trim() && results.total) ?
                <div className={clsx('position-absolute bg-white border border-1 pt-3', responsive && 'w-100 start-0', !responsive && 'shadow-lg rounded-top rounded-bottom col-6 col-xl-5 col-xxl-4 rounded-4')} style={{top:'53px'}}>
                    <ul className={'list-unstyled mb-0'}>
                        {
                            results.items.map((result, key) => <ResultItem result={result} key={key} isSelected={selectedIndex === key}/>)
                        }
                        <li className={'text-center border-top d-flex align-items-center rounded-bottom'}>
                            <a className={clsx("text-decoration-none text-muted px-2 pt-2 w-100 text-sm fw-bold hover-primary py-2 rounded-bottom", selectedIndex === results.items.length && 'selected')} href={results.route}>
                                {t('See results', { count: results.total })}
                            </a>
                        </li>
                    </ul>
                </div> : null
        }
        </>
    )
}

function ResultItem ({result, isSelected}) {

    const [loading, setLoading] = useState(true);

    const imageLoad = () => {
        setLoading(false);
    }

    return (
        <li>
            <a
                href={result.url}
                className={clsx("d-flex align-items-start gap-2 justify-content-start text-decoration-none px-3 py-2 hover-primary", isSelected && 'selected')}
            >
                {
                    loading &&
                    <img
                        width={100}
                        className={'img-fluid'}
                        src={'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAs4AAAGUCAYAAAAlEaQgAAAAAXNSR0IArs4c6QAAF2hJREFUeF7t1qERhEAQRUHGkn9mgCUOFFwERz1Pr/5mu0a8OY7jue978QgQIECAAAECBAgQ+C8w27Y9gAgQIECAAAECBAgQeBcQzi6EAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIArPv+xN2JgQIECBAgAABAgQ+LTDneV7rui4z82kInydAgAABAgQIECDwJqCW3QcBAgQIECBAgACBIPADYwZ7BgQTqgYAAAAASUVORK5CYII='}
                        alt={'default '}
                    />
                }
                <img className={clsx('img-fluid', loading && 'd-none', !loading && 'd-block')} width={100} src={result.thumbnail} alt="" onLoad={imageLoad} />
                <div className={'text-sm'}>
                    <div className={'text-black'} dangerouslySetInnerHTML={{__html: result._formatted.title}}></div>
                    <div className={'text-muted'} dangerouslySetInnerHTML={{__html: result._formatted.user}}></div>
                </div>
            </a>
        </li>
    )
}
