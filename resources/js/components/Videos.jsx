import {useState, useEffect, useRef, useCallback} from 'preact/hooks';
import {usePaginateFetch} from "../hooks/usePaginateFetch";
import {memo} from "preact/compat";
import Video from "./Video";
import { useInView } from 'react-intersection-observer';


export default function Videos ({}) {

    const {items: videos, setItems: setVideos, load, loading, count, setCount, hasMore, setNext} =  usePaginateFetch('/api/videos/')
    const [primaryLoading, setPrimaryLoading] = useState(true)
    const { ref, inView } = useInView({
        threshold: 0.5
    });

    useEffect( async () => {
        //setPrimaryLoading(true);
        await load()
        setPrimaryLoading(false);
    }, []);

    useEffect( async () => {

        if (inView) {
            await load()
        }

    }, [inView]);

    return (
        <div className={''}>
            {
                (primaryLoading) ?
                    <div className="spinner-border" role="status">
                        <span className="visually-hidden">Loading...</span>
                        <div>dd</div>
                    </div>
                    :
                    <div className={''}>
                        <div id="video-container" className="row gx-4">
                            {videos.map(video => <Video key={video.id} video={video}/>)}
                        </div>
                        <div ref={ref} className={'d-flex justify-content-center'}>
                            <div id={'loader'} className="spinner-border" role="status">
                                <span className="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
            }
        </div>
    )
};
