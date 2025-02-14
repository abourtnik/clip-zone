import {useContext, useEffect, useState} from "preact/hooks";
import {useSearchQuery} from "@/hooks";
import {getPlaylistVideos, searchVideos} from "@/api/clipzone";
import { ReactSortable } from "react-sortablejs/src/index";
import moment from "moment";
import {InfiniteData, QueryClient, QueryClientProvider, useInfiniteQuery, useQueryClient} from "@tanstack/react-query";
import {Paginator, VideoType} from "@/types";
import {OverlayTrigger, Tooltip} from "react-bootstrap";
import {createContext, Fragment} from "preact";
import {useInView} from "react-intersection-observer";
import {produce} from "immer";
import ImageLoaded from "@/components/Images/ImageLoaded";

type Props = {
    uuid?: string
}

export const PlaylistContext = createContext<{uuid?: string}>({});

export function Main({uuid} : Props) {

    const { ref, inView} = useInView();

    const initialData = {
        pageParams: [1],
        pages: [{
            data: [] as VideoType[],
            meta: {
                current_page: 1,
                from: 1,
                last_page: 1,
                per_page: 24,
                to: 1,
                total: 0,
            },
            links:{
                next: null
            }
        }]
    }

    const {
        data: videos,
        isLoading,
        isError,
        refetch,
        isFetchingNextPage,
        fetchNextPage,
        hasNextPage,
    } = useInfiniteQuery({
        queryKey: ['playlist', uuid, 'videos'],
        initialData: uuid === undefined ? initialData : undefined,
        queryFn: ({pageParam}) => getPlaylistVideos(uuid ?? null, pageParam),
        initialPageParam: 1,
        enabled: uuid !== undefined,
        staleTime: Infinity,
        getNextPageParam: (lastPage, allPages, lastPageParam) => {
            if (lastPage.meta.current_page === lastPage.meta.last_page) {
                return undefined
            }
            return lastPageParam + 1
        }
    });

    useEffect( () => {
        if (inView && !isFetchingNextPage && !isError) {
            fetchNextPage()
        }
    }, [inView]);

    return(
        <PlaylistContext.Provider value={{uuid: uuid}}>
            <div className="card shadow-soft pb-0">
                <div className="card-body px-0 pb-0">
                    <h5 className="text-primary px-3">Manage videos</h5>
                    <hr className={'mb-0'}/>
                    <div className={'d-flex'}>
                        <div className="w-50 border-end">
                            <div className={'text-center border-bottom d-flex align-items-center justify-content-center'} style={{height: '71px'}}>
                                <h6 className={'mb-0'}>Playlist videos</h6>
                            </div>
                            <div className={'overflow-auto'} style="height:590px">
                                {
                                    isLoading &&
                                    <div className={'d-flex justify-content-center align-items-center h-100'}>
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                }
                                {
                                    isError &&
                                    <div className={'d-flex justify-content-center align-items-center h-100'}>
                                        <div class="border border-1 bg-light text-center p-3">
                                            <i class="fa-solid fa-triangle-exclamation fa-3x"></i>
                                            <h3 class="h5 my-3 fw-normal">Something went wrong !</h3>
                                            <p class="text-muted">If the issue persists please contact us.</p>
                                            <button className="btn btn-primary rounded-5 text-uppercase btn-sm" onClick={() => refetch()}>
                                                Try again
                                            </button>
                                        </div>
                                    </div>
                                }
                                <div className={'h-100'}>
                                    {videos && <SelectedVideos videos={videos}/>}
                                    {
                                        isFetchingNextPage &&
                                        <div className={'d-flex justify-content-center align-items-center py-3'}>
                                            <div class="spinner-border text-primary spinner-border-sm" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    }
                                    {hasNextPage && <span ref={ref}></span>}
                                </div>
                            </div>
                        </div>
                        <Search/>
                    </div>
                </div>
            </div>
        </PlaylistContext.Provider>

    )
}

type SelectedVideosProps = {
    videos: InfiniteData<Paginator<VideoType>>,
}

function SelectedVideos({videos}: SelectedVideosProps) {

    const queryClient = useQueryClient();

    const {uuid} = useContext(PlaylistContext);

    const remove = (index: number) => {
        queryClient.setQueriesData({queryKey: ['playlist', uuid, 'videos']}, (oldData: InfiniteData<Paginator<VideoType>> | undefined) => {
            if (!oldData) return undefined;
            return produce(oldData, draft => {
                draft.pages.forEach((page, i) => {
                    page.data.forEach((v, j) => {
                        if (i + j === index) {
                            page.data.splice(j, 1);
                        }
                    })
                });
            });
        })
    }

    const setVideos = (sorted: VideoType[]) => {
        queryClient.setQueriesData({queryKey: ['playlist', uuid, 'videos']}, (oldData: InfiniteData<Paginator<VideoType>> | undefined) => {
            if (!oldData) return undefined;
            return produce(oldData, draft => {
                // ! This work only for if video has one page !
                draft.pages[0].data = sorted
            });
        })
    }

    return (
        <Fragment>
            {
                videos?.pages.flatMap((page => page.data)).length > 0 ?
                <ReactSortable
                    tag="ul"
                    list={videos?.pages.flatMap((page => page.data))}
                    setList={setVideos}
                    className={'list-group list-group-flush mb-0 overflow-auto'}
                    ghostClass='bg-light-dark'
                    handle={'.handle'}
                    animation={150}
                >
                    {videos.pages.map((group, i) => (
                        <Fragment key={i}>
                            {group.data.map((video, j) => (
                                <li className={'list-group-item p-0'}>
                                    <div className="d-flex align-items-center justify-content-between gap-3 px-3 py-2">
                                        <div className={'d-flex align-items-center gap-2 d-flex w-100'}>
                                            <div className={'handle cursor-move'}>
                                                <i className="fa-solid fa-bars"></i>
                                            </div>
                                            {
                                                video.is_private ?
                                                    <div className='d-flex flex-column justify-content-center align-items-center w-100 gap-2 alert alert-secondary mb-0'>
                                                        <i className="fa-solid fa-lock fa-1x"></i>
                                                        <div className={'text-sm fw-bold text-center'}>This video is private</div>
                                                        <div className={'text-sm'}>The author update video status to private</div>
                                                    </div>
                                                    :
                                                    <div className={'d-flex flex-column flex-xl-row align-items-start gap-2 justify-content-start px-3 py-2'}>
                                                        <div className={'position-relative'}>
                                                            <ImageLoaded style={{width: '150px', height: '84px'}} source={video.thumbnail} title={video.title}/>
                                                            <small
                                                                className="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded"
                                                                style="font-size: 0.70rem;">
                                                                {video.formated_duration}
                                                            </small>
                                                        </div>
                                                        <div>
                                                            <div className={'text-black fw-bold text-sm mb-1 text-break'}>{video.title}</div>
                                                            <div className={'text-muted text-sm'}>{video.user.username}</div>
                                                            <div className={'text-muted text-sm'}>{video.views} views • {moment(video.publication_date).fromNow()}</div>
                                                        </div>
                                                    </div>
                                            }
                                        </div>
                                        <OverlayTrigger placement="top"
                                                        overlay={
                                                            <Tooltip>
                                                                Remove
                                                            </Tooltip>
                                                        }
                                        >
                                            <button
                                                className="bg-transparent py-1 btn-sm"
                                                type="button"
                                                onClick={() => remove(i + j)}
                                            >
                                                <i className="fa-solid fa-xmark"></i>
                                            </button>
                                        </OverlayTrigger>

                                    </div>
                                    <input type="hidden" name={'videos[]'} value={video.id}/>
                                </li>
                            ))}
                        </Fragment>
                    ))}
                </ReactSortable>
                :
                <div className={'d-flex flex-column gap-1 justify-content-center align-items-center px-3 h-100 w-100'}>
                    <div className={'fw-bold fs-5'}>No videos added</div>
                    <p className={'text-muted text-sm text-center'}>Add videos to your playlist by selecting your videos or videos from other channels</p>
                </div>
            }
        </Fragment>
    )

}

type SearchResultProps = {
    video : VideoType,
    index: number,
    results: {data: VideoType[]}
}

function SearchResult ({video, index, results} : SearchResultProps) {

    const queryClient = useQueryClient();

    const {uuid} = useContext(PlaylistContext);

    const addVideo = (index: number) => {
        queryClient.setQueriesData({queryKey: ['playlist', uuid, 'videos']}, (oldData: InfiniteData<Paginator<VideoType>> | undefined) => {
                if (!oldData) return undefined
                return produce(oldData, draft => {
                    draft.pages[0].data.unshift(results.data[index]);
                    draft.pages[0].meta.total = draft.pages[0].meta.total! + 1;
                });
            }
        )
    }

    return (
        <li style={{cursor: 'pointer'}} className={'list-group-item p-0'} onClick={() => addVideo(index)} >
            <div className="d-flex flex-column flex-xl-row align-items-start gap-2 justify-content-start px-3 py-2 hover-primary">
                <div className={'position-relative'}>
                    <ImageLoaded style={{width: '150px', height: '84px'}} source={video.thumbnail} title={video.title}/>
                    <small className="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                        {video.formated_duration}
                    </small>
                </div>
                <div>
                    <div className={'text-black fw-bold text-sm mb-1 text-break'}>
                        {video.title}
                    </div>
                    <div className={'text-muted text-sm'}>
                        {video.user.username}
                    </div>
                    <div className={'text-muted text-sm'}>
                        {video.views} views • {moment(video.publication_date).fromNow()}
                    </div>
                </div>
            </div>
        </li>
    )
}

function Search() {

    const [query, setQuery] = useState<string>('');

    const {data: results, isFetching} = useSearchQuery({
        query: query,
        key: 'playlist.search',
        searchFn: searchVideos,
    });

    return (
        <div className="w-50  pt-3 px-0">
            <div className={'position-relative pb-3 border-bottom'}>
                <form className="d-flex w-100 position-relative px-3">
                    <input
                        //onMouseDown={(e) => false}
                        onChange={(e) => setQuery(e.currentTarget.value)}
                        className="form-control rounded-5 "
                        type="search"
                        placeholder="Search Videos"
                        aria-label="Search"
                        name="q"
                        value={query}
                    />
                    {
                        isFetching &&
                        <div className="position-absolute top-50 right-5 translate-middle">
                            <div className={'spinner-border spinner-border-sm'} role="status">
                                <span className="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    }
                </form>
            </div>
            <div className={'overflow-auto'} style="height:590px">
                {
                    results && results.data.length > 0 && (
                        <ul className={'list-group list-group-flush mb-0'}>
                            {
                                results.data.map((result, index) => (
                                    <SearchResult video={result} index={index} key={result.id} results={results}/>
                                ))
                            }
                        </ul>
                    )
                }
                {
                    results && results.data.length === 0 && (
                        <div className={'d-flex justify-content-center align-items-center px-3 h-100 w-100'}>
                            <p className={'text-muted text-sm text-center'}>No results. Please try
                                again.</p>
                        </div>
                    )
                }
            </div>
        </div>
    )
}

export function Playlist(props: Props) {

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
