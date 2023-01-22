import {useState, useEffect} from 'preact/hooks';
import {usePaginateFetch} from "../hooks";
import Video from "./Video";
import { useInView } from 'react-intersection-observer';
import {Video as Skeleton} from "./Skeletons";

export default function Videos ({url, skeletons = 12}) {

    const {items: videos, load, loading, hasMore} =  usePaginateFetch(url)
    const [primaryLoading, setPrimaryLoading] = useState(true)
    const {ref,inView} = useInView();

    useEffect( async () => {
        if (inView && !loading) {
            await load()
        }
    }, [inView]);

    useEffect( async () => {
        setPrimaryLoading(true);
        await load()
        setPrimaryLoading(false);
    }, [url]);

    return (
        <>
        {
            (primaryLoading) ?
                <div className="row gx-3 gy-5">
                    {[...Array(parseInt(skeletons)).keys()].map(i => <Skeleton key={i}/>)}
                </div>
                :
                <>
                    {
                        (videos.length) ?
                            <>
                                <div className="row gx-3 gy-4">
                                    {videos.map(video => <Video key={video.id} video={video}/>)}
                                </div>
                                {
                                    hasMore &&
                                    <div ref={ref} className="row gx-3 gy-4">
                                        {[...Array(12).keys()].map(i => <Skeleton key={i}/>)}
                                    </div>
                                }
                            </> :
                            <div className={'h-100 d-flex align-items-center justify-content-center'}>
                                <p> No video </p>
                            </div>
                    }
                </>
        }
        </>
    )
};
