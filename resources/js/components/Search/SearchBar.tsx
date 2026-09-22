import {useState, useRef} from 'preact/hooks';
import {useTranslation} from "react-i18next";
import clsx from 'clsx';
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";
import {useSearchQuery, useClickOutside, useKeyboardNavigate} from "@/hooks";
import {search} from "@/api/clipzone";
import {SearchUserType, SearchVideoType} from "@/types";
import type { TargetedEvent, ComponentType } from "preact"
import {Loader} from "@/components/Commons";
import {ResultVideo, ResultUser} from "@/components/Search"

type Props = {
    query?: string,
    responsive?: boolean
}

function Main ({responsive = true} : Props) {

    const { t } = useTranslation();

    const form = useRef<HTMLFormElement>(null);

    const searchParams = new URLSearchParams(window.location.search).get('q') ?? '';

    const [query, setQuery] = useState<string>(searchParams);
    const [showResults, setShowResults] = useState<boolean>(false);

    const {data: results, isFetching} = useSearchQuery({
        query: query,
        key: 'search',
        searchFn: search,
    });

    useClickOutside(form, () => setShowResults(false))

    const {index, navigate, resetIndex} = useKeyboardNavigate({
        length: results && results.items.length + 1,
        onSelect: (index: number) => {
            window.location.href = results!.items[index]?.url ?? '/search?q=' + query;
        },
        onDefaultSelect: () => {
            window.location.href = '/search?q=' + query;
        }
    });

    const handleSubmit = (e: TargetedEvent<HTMLFormElement>) => {
        if (index !== null) {
            e.preventDefault();
        }
    }

    const handleChange = (e: TargetedEvent<HTMLInputElement>) => {
        setQuery(e.currentTarget.value)
        resetIndex()
    }

    return (
        <>
        <form ref={form} method="GET" className="d-flex w-100 mb-0" role="search" action="/search" onSubmit={handleSubmit}>
            <div className="input-group flex-nowrap">
                <div className={'position-relative'} style={{flex: '1 1 auto'}}>
                    <input
                        onClick={() => setShowResults(true)}
                        onChange={handleChange}
                        className="form-control rounded-5 tw:rounded-e-none! border border-secondary"
                        type="search"
                        placeholder={t("Search")}
                        aria-label="Search"
                        name="q"
                        value={query}
                        onKeyDown={navigate}
                        maxLength={255}
                    />
                    {
                        isFetching &&
                        <div className="position-absolute top-50 right-0 translate-middle" >
                            <Loader small={true}/>
                        </div>
                    }
                </div>
                <button className="btn btn-outline-secondary rounded-5 tw:rounded-s-none! px-4" type="submit">
                    <i className="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
        {
            (showResults && results && results.total > 0) &&
                <div className={clsx('position-absolute bg-white border border-1 pt-3', responsive && 'w-100 start-0', !responsive && 'shadow-lg rounded-top rounded-bottom col-6 col-xl-5 col-xxl-4 rounded-4')} style={{top:'53px'}}>
                    <ul className={'list-unstyled mb-0'}>
                        {
                            results.items.map((result, key) => <ResultItem result={result} key={key} isSelected={index === key}/>)
                        }
                        <li className={'text-center border-top d-flex align-items-center rounded-bottom'}>
                            <a className={clsx("text-decoration-none text-muted px-2 pt-2 w-100 text-sm fw-bold hover-primary py-2 rounded-bottom", index === results.items.length && 'selected')} href={results.route}>
                                {t('See results', { count: results.total })}
                            </a>
                        </li>
                    </ul>
                </div>
        }
        </>
    )
}

const components = {
    video: ResultVideo,
    user: ResultUser,
} as const;

type ResultItemProps = {
    result: SearchVideoType | SearchUserType
    isSelected : boolean
}
function ResultItem ({result, isSelected} : ResultItemProps) {

    let Component = components[result.type] as ComponentType<{
        result: typeof result;
    }>;

    if (!Component) return null;

    return (
        <li>
            <a
                href={result.url}
                className={clsx("d-flex gap-2 justify-content-start text-decoration-none px-3 py-2 hover-primary", isSelected && 'selected')}
            >
                <Component result={result}/>
            </a>
        </li>
    )
}


export function SearchBar(props: Props) {

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
