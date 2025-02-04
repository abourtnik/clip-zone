import {useState, useRef} from 'preact/hooks';
import {useSearchQuery, useClickOutside, useKeyboardNavigate} from "@/hooks";
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";
import {searchModel} from "@/api/clipzone";
import {ChangeEvent} from "react";

type Props = {
    endpoint: string,
    name: string,
    label?: string | null,
    value: string | null,
}

function Main ({endpoint, name, label = null, value = null} : Props) {

    const defaultValue: { value: string, label: string} | null = value ? JSON.parse(value) : null;

    const input = useRef<HTMLInputElement>(null);
    const finalInput = useRef<HTMLInputElement>(null);

    const [query, setQuery] = useState<string>(defaultValue?.label ?? '');
    const [showResults, setShowResults] = useState<boolean>(false);

    const {data: results, isFetching} = useSearchQuery({
        query: query,
        key: 'search',
        searchFn: () => searchModel(endpoint, query)
    });

    useClickOutside(input, () => setShowResults(false))

    const select = (index: number) => {
        const result = results!.data[index];
        finalInput.current!.value = result.value
        setQuery(result.label)
        setShowResults(false)
    }

    const {index, navigate, resetIndex} = useKeyboardNavigate({
        length: results?.data.length,
        onSelect: select,
        onDefaultSelect: () => null
    });

    const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
        setShowResults(true)
        setQuery(e.currentTarget.value)
        resetIndex()
    }

    return (
        <div className={'position-relative'}>
            <label htmlFor={name} className="form-label fw-bold">{label ?? name.charAt(0).toUpperCase() + name.slice(1)}</label>
            <div className={'position-relative'}>
                <input
                    onClick={() => setShowResults(true)}
                    ref={input}
                    onChange={handleChange}
                    type="search"
                    className="form-control"
                    id={name}
                    placeholder={'Search ' + name + ' ...'}
                    onKeyDown={navigate}
                    value={query}
                />
                {
                    isFetching &&
                    <div className="position-absolute top-50 right-0 translate-middle" >
                        <div className={'spinner-border spinner-border-sm'} role="status">
                            <span className="visually-hidden">Loading...</span>
                        </div>
                    </div>
                }
            </div>
            {
                (showResults && results) &&
                <div className={'position-absolute w-100 rounded-2 bg-white shadow-lg border border-1'} style={{top:'75px'}}>
                    {
                        results.data.length ?
                            <ul className={'list-unstyled mb-0'}>
                                {
                                    results.data.map((result, key) => (
                                        <li className={'text-black px-3 py-2 hover-primary cursor-pointer ' + (index === key ? 'selected' : null)} onClick={() => select(key)} key={key}>
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
            <input type="hidden" name={name} ref={finalInput} defaultValue={defaultValue?.value}/>
        </div>

    )
}

export function SearchModel(props: Props) {

    const queryClient = new QueryClient({
        defaultOptions: {
            queries: {
                retry: false,
            }
        }
    });

    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props} />
        </QueryClientProvider>
    )
}

