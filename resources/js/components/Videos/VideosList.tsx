import {Fragment} from "preact";
import {Video} from "./Video";
import {VideoSkeleton} from "@/components/Skeletons/VideoSkeleton";
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";
import {getVideos} from '@/api/clipzone'
import {useCursorQuery} from "@/hooks";
import {ApiError} from "@/components/Commons";

type Props = {
    url: string,
}

function Main ({url} : Props) {

    const {
        data: videos,
        isLoading,
        isError,
        refetch,
        error,
        isFetchingNextPage,
        hasNextPage,
        ref,
    } = useCursorQuery({
        queryKey: ['videos', url],
        queryFn: ({pageParam}) => getVideos(url, pageParam),
    });

    return (
        <>
            {
                isError &&
                <div className={'row align-items-center h-75 mt-5'}>
                    <div className={'col-10 offset-1 col-xxl-8 offset-xxl-2'}>
                        <ApiError refetch={refetch} error={error}/>
                    </div>
                </div>

            }
            <div
                className="row mx-0 gx-3 gy-3 gy-sm-4 row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-3">
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
                    (isFetchingNextPage || isLoading) &&
                    [...Array(16).keys()].map(i => <VideoSkeleton key={i}/>)
                }
                {hasNextPage && <span ref={ref}></span>}
            </div>
        </>
    )
}

export function VideosList(props: Props) {

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
