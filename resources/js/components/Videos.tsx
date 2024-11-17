import {Fragment} from "preact";
import {useEffect} from 'preact/hooks';
import Video from "./Video";
import { useInView } from 'react-intersection-observer';
import {VideoSkeleton} from "@/components/Skeletons/VideoSkeleton";
import {QueryClient, QueryClientProvider, useInfiniteQuery} from "@tanstack/react-query";
import {getVideos} from '@/api/clipzone'

type Props = {
    url: string,
}

function Main ({url} : Props) {

    const { ref, inView} = useInView()

    const {
        data: videos,
        isLoading,
        isError,
        refetch,
        isFetching,
        fetchNextPage,
        hasNextPage,
    } = useInfiniteQuery({
        queryKey: ['videos', url],
        queryFn: ({pageParam}) => getVideos(url, pageParam),
        initialPageParam: 1,
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

    return (
        <>
            {
                isError &&
                <div class="row align-items-center h-75 mt-5 ">
                    <div class="col-10 offset-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                        <div class="row">
                            <div
                                class="col-6 d-none d-lg-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                                <i class="fa-solid fa-triangle-exclamation fa-10x"></i>
                            </div>
                            <div
                                class="col-12 col-lg-6 py-5 px-3 px-sm-5 d-flex align-items-center justify-content-center text-center">
                                <div>
                                    <h1 class="h3 mb-3 fw-normal">Something went wrong !</h1>
                                    <p class="text-muted">If the issue persists please contact us.</p>
                                    <button className="btn btn-primary rounded-5 text-uppercase" onClick={refetch}>
                                        Try again
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            }
            <div
                className="row mx-0 gx-3 gy-3 gy-sm-4 row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4">
                {
                    videos &&
                    <>
                        {videos.pages.map((group, i) => (
                            <Fragment key={i}>
                                {group.data.map((video) => <Video video={video}/>)}
                            </Fragment>
                        ))}
                    </>
                }
                {
                    (isFetching || isLoading) &&
                    [...Array(12).keys()].map(i => <VideoSkeleton key={i}/>)
                }
                {hasNextPage && <span ref={ref}></span>}
            </div>
        </>
    )
}

export default function Videos(props: Props) {

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
