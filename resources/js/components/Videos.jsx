import {useState, useEffect} from 'preact/hooks';
import {usePaginateFetch} from "../hooks";
import Video from "./Video";
import { useInView } from 'react-intersection-observer';
import Skeleton from "./Skeleton";

export default function Videos ({url}) {

    const {items: videos, setItems: setVideos, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch(url)
    const [primaryLoading, setPrimaryLoading] = useState(true)
    const { ref, inView } = useInView({
        threshold: 0.5
    });

    useEffect( async () => {
        await load()
        setPrimaryLoading(false);
    }, []);

    /*useEffect( async () => {

        if (inView) {
            await load()
        }

    }, [inView]);*/

    return (
        <>
        {
            (primaryLoading) ?
                <div id="video-container" className="row gx-4">
                    {[...Array(24).keys()].map(i => <Skeleton key={i}/>)}
                </div>
                :
                <>
                    {
                        (videos.length) ?
                            <>
                                <div id="video-container" className="row gx-4">
                                    {videos.map(video => <Video key={video.id} video={video}/>)}
                                </div>
                                {
                                    hasMore &&
                                    <div ref={ref} className={'d-flex justify-content-center'}>
                                        <div id={'loader'} className="spinner-border" role="status">
                                            <span className="visually-hidden">Loading...</span>
                                        </div>
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
