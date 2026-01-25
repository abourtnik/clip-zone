import {useEffect, useRef, useState} from "preact/hooks";
import {SubtitleType} from "@/types";
import {AUTOPLAY_NEXT_VIDEO_KEY, AUTOPLAY_VIDEO_KEY} from "@/components/Switchs";
import {viewVideo, player} from "@/api/clipzone";
import {QueryClient, QueryClientProvider, useMutation, useQuery} from "@tanstack/react-query";
import {Ad} from "@/components/Videos/Ad";
import {Loader, ApiError} from "@/components/Commons";
import {TargetedEvent} from "react";
import {clsx} from "clsx";

type Props = {
    video_uuid: string,
    next_video?: string|null,
}

const VIEW_COUNT_DURATION: number = 1;

export function Main ({video_uuid, next_video} : Props) {

    const videoElement = useRef<HTMLVideoElement>(null);
    const volume = JSON.parse(localStorage.getItem('volume') as string) || {value: 0.5, muted: false};
    const timeCode = new URLSearchParams(window.location.search).get('t') ?? false;
    const autoplayVideo = JSON.parse(localStorage.getItem(AUTOPLAY_VIDEO_KEY) || 'true');

    let watchedTime = 0;
    let lastTime: number | null = null;

    const {
        data: video,
        isLoading,
        isError,
        refetch,
        error,
    } = useQuery({
        queryKey: ['video.player', video_uuid],
        queryFn: () => player(video_uuid),
    });

    const [showAd, setShowAds] = useState<boolean|null>(null);
    const [isReady, setIsReady] = useState<boolean>(false);

    const {mutate: view} = useMutation({
        mutationFn: () => viewVideo(video_uuid),
        mutationKey: ['videos.view', video_uuid],
    });

    const hasViewed = useRef(false);

    const handleVolumeChange = (event: TargetedEvent<HTMLVideoElement>) => {
        localStorage.setItem("volume", JSON.stringify({
            value: event.currentTarget.volume.toFixed(2),
            muted: event.currentTarget.muted
        }));
    };

    const handleVideoEnded = (event: TargetedEvent<HTMLVideoElement>) => {

        const autoplayNextVideo = JSON.parse(localStorage.getItem(AUTOPLAY_NEXT_VIDEO_KEY) || 'false');

        if(autoplayNextVideo && next_video) {
            window.location.replace(next_video);
        }
    };

    const handleLoaded = (event: TargetedEvent<HTMLVideoElement>) => {

        videoElement.current!.volume = volume.value;
        videoElement.current!.muted = volume.muted;

        if (timeCode) {
            videoElement.current!.currentTime = parseInt(timeCode);
        }
    };

    const handleTimeUpdate = (event: TargetedEvent<HTMLVideoElement>) => {

        const video = event.currentTarget;

        if (video.paused || video.seeking) {
            lastTime = null;
            return;
        }

        const current = video.currentTime;

        if (lastTime !== null) {
            const delta = current - lastTime;

            // Ignore seeks / jumps
            if (delta > 0 && delta < 1) {
                watchedTime += delta;
            }
        }

        lastTime = current;

        if(watchedTime > VIEW_COUNT_DURATION && !hasViewed.current) {
             hasViewed.current = true;
             view();
         }
    };

    useEffect(() => {
        if (video) {
            setShowAds(video.show_ad);
        }
    }, [video]);

    return (
        <>
            {
                (isLoading || showAd === null)  &&
                <div className="d-flex justify-content-center align-items-center h-100 w-100 bg-dark">
                    <Loader/>
                </div>
            }
            {
                isError &&
                <div className="d-flex justify-content-center align-items-center h-100 w-100 bg-dark">
                    <ApiError error={error} refetch={refetch}/>
                </div>

            }
            {
                (video && showAd !== null) &&
                <>
                    {showAd && <Ad setAds={setShowAds} />}
                    {
                        !showAd &&
                        <div className={'position-relative h-100 w-100'}>
                            <video
                                ref={videoElement}
                                onTimeUpdate={handleTimeUpdate}
                                onLoadedMetadata={handleLoaded}
                                onVolumeChange={handleVolumeChange}
                                onEnded={handleVideoEnded}
                                controls={true}
                                onCanPlay={() => setIsReady(true)}
                                className={clsx("w-100 border border-1 rounded h-100", !isReady && 'd-none')}
                                controlsList="nodownload"
                                poster={video.thumbnail}
                                onContextMenu={(e: any) => e.preventDefault()}
                                playsInline
                                autoPlay={autoplayVideo}
                                muted={volume.muted}
                            >
                                <source src={video.file} type="video/mp4"/>
                                {
                                    video.subtitles.map((subtitle: SubtitleType) => (
                                        <track
                                            key={subtitle.id}
                                            label={subtitle.name}
                                            kind="subtitles"
                                            srcLang={subtitle.language}
                                            src={subtitle.file_url}
                                        />
                                    ))
                                }
                            </video>
                            {
                                !isReady &&
                                <div className="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark" style={{ pointerEvents: "all" }}>
                                    <Loader/>
                                </div>
                            }
                        </div>
                    }
                </>
            }
        </>
    )
}

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            retry: false,
        }
    }
});

export function Player(props: Props) {
    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props} />
        </QueryClientProvider>
    )
}
