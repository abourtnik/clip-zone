import {useState, useEffect} from 'preact/hooks';
import {usePaginateFetch} from "../hooks";
import Video from "./Video";
import { useInView } from 'react-intersection-observer';
import {Video as Skeleton} from "./Skeletons";

export default function Videos ({url}) {

    const {items: videos, load, loading, hasMore} =  usePaginateFetch(url)
    const [primaryLoading, setPrimaryLoading] = useState(true)
    const {ref,inView} = useInView();

    useEffect( async () => {
        await load()
        setPrimaryLoading(false);
    }, []);

    useEffect( async () => {
        if (inView && !loading) {
            await load()
        }
    }, [inView]);

    return (
        <>
        {
            (primaryLoading) ?
                <div className="row gx-4">
                    {[...Array(24).keys()].map(i => <Skeleton key={i}/>)}
                </div>
                :
                <>
                    {
                        (videos.length) ?
                            <>
                                <div id="video-container" className="row gx-3">
                                    {videos.map(video => <Video key={video.id} video={video}/>)}
                                </div>
                                {
                                    hasMore &&
                                    <div ref={ref} className="row gx-3">
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
