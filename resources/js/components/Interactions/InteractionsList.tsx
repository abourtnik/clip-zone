import { useState, useEffect} from 'preact/hooks';
import {useInView} from "react-intersection-observer";
import {Subscribe} from "@/components/Actions";
import {QueryClient, QueryClientProvider, useInfiniteQuery} from "@tanstack/react-query";
import {getInteractions} from "@/api/clipzone";
import {Fragment} from "preact";
import {InteractionSkeleton} from "@/components/Skeletons/InteractionSkeleton";
import {InteractionType, InteractionsFilters} from "@/types";
import {ChangeEvent} from "react";
import {useDebounce} from "@/hooks/useDebounce";
import moment from "moment/moment";

type Props = {
    target: number
}

function Main ({target} : Props) {

    const [filter, setFilter] = useState<InteractionsFilters>('all');
    const [search, setSearch] = useState<string>('');

    const debouncedSearchQuery: string = useDebounce(search, 300);

    const { ref, inView} = useInView()

    const {
        data: interactions,
        isLoading,
        isError,
        refetch,
        isFetching,
        fetchNextPage,
        hasNextPage,
    } = useInfiniteQuery({
        queryKey: ['interactions', target, filter, debouncedSearchQuery],
        queryFn: ({pageParam}) => getInteractions(target, filter, debouncedSearchQuery, pageParam),
        initialPageParam: 1,
        gcTime: 0,
        getNextPageParam: (lastPage, allPages, lastPageParam) => {
            if (lastPage.meta.current_page === lastPage.meta.last_page) {
                return undefined
            }
            return lastPageParam + 1
        }
    });

    useEffect( () => {
        if (inView && !isFetching && !isError) {
            fetchNextPage()
        }
    }, [inView]);

    const filtering = (type: InteractionsFilters) => {
        if (type !== filter) {
            setFilter(type)
        }
    }
    const searching = (e: ChangeEvent<HTMLInputElement>) => setSearch(e.currentTarget.value)

    const activeButton = (type: InteractionsFilters) => filter === type ? 'primary ' : 'outline-primary ';

    return (
        <div style="height: 450px;">
            <div className={'d-flex gap-2 align-items-center'}>
                <button onClick={() => filtering('all')} className={'btn btn-' + activeButton('all') + 'btn-sm'}>All</button>
                <button onClick={() => filtering('up')} className={'d-flex align-items-center gap-2 btn btn-' + activeButton('up') + 'btn-sm'}>
                    <span>Only</span>
                    <i className="fa-solid fa-thumbs-up"></i>
                </button>
                <button onClick={() => filtering('down')} className={'d-flex align-items-center gap-2 btn btn-' + activeButton('down') + 'btn-sm'}>
                    <span>Only</span>
                    <i className="fa-solid fa-thumbs-down"></i>
                </button>
                <input onChange={searching} type="text" className={'form-control form-control-sm'} placeholder="Search user" aria-label="Search"/>
            </div>
            <hr/>
            <small className={'text-muted mb-1 d-block'}>{isLoading ? 'Loading ...' : interactions && interactions.pages[0].meta.total + ' Results'}</small>
            <hr/>
            {
                isError &&
                <div class="border border-1 bg-light text-center p-3">
                    <i class="fa-solid fa-triangle-exclamation fa-3x"></i>
                    <h3 class="h5 my-3 fw-normal">Something went wrong !</h3>
                    <p class="text-muted">If the issue persists please contact us.</p>
                    <button className="btn btn-primary rounded-5 text-uppercase" onClick={() => refetch()}>
                        Try again
                    </button>
                </div>
            }
            <div className="my-3" style="overflow-y: auto;height:330px;">
                <ul className="list-group">
                    {

                        interactions &&
                        <>
                            {interactions.pages.map((group, i) => (
                                <Fragment key={i}>
                                    {group.data.map((interaction) => <Interaction interaction={interaction}/>)}
                                </Fragment>
                            ))}
                        </>
                    }
                    {
                        (isFetching || isLoading) &&
                        [...Array(6).keys()].map(i => <InteractionSkeleton key={i}/>)
                    }
                </ul>
                {hasNextPage && <span ref={ref}></span>}
            </div>
        </div>
    );
}

export function InteractionList(props: Props) {

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

function Interaction ({interaction} : {interaction: InteractionType}) {
    return (
        <li className="list-group-item d-flex flex-wrap justify-content-between align-items-center">
            <div className={'d-flex align-items-center gap-2 gap-sm-4'}>
                <div className={'p-2 badge bg-' + (interaction.status ? 'success' : 'danger')}>
                    {interaction.status ? <i className="fa-solid fa-thumbs-up"></i> : <i className="fa-solid fa-thumbs-down"></i>}
                </div>
                <div className={'d-flex align-items-center gap-2 ap-sm-4'}>
                    <a href={interaction.user.route} className="d-flex align-items-center gap-2 text-decoration-none">
                        <img className="rounded" src={interaction.user.avatar} alt={interaction.user.username + ' avatar'} style="width: 40px;"/>
                    </a>
                    <div className={'d-flex flex-column'}>
                        <span className={'text-sm'}>{interaction.user.username}</span>
                        <div className={'text-muted text-sm'}>{moment(interaction.perform_at).fromNow()}</div>
                    </div>
                </div>
            </div>
            {
                interaction.user.id !== window.USER?.id &&
                <Subscribe isSubscribe={interaction.user.subscribed_by_auth_user} user={interaction.user.id} size={'sm'}/>
            }
        </li>
    )
}
