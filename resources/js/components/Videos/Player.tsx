import {useEffect, useRef} from "preact/hooks";
import {SubtitleType} from "@/types";
import {AUTOPLAY_VIDEO_KEY, AUTOPLAY_NEXT_VIDEO_KEY} from "@/components/Switchs";
import {viewVideo} from "@/api/clipzone";
import {QueryClient, QueryClientProvider, useMutation} from "@tanstack/react-query";

type Props = {
    video_id: string,
    thumbnail_url: string,
    file_url: string,
    next_video?: string|null,
    subtitles?: string
}

const VIEW_COUNT_DURATION = 2;

export function Main ({video_id, thumbnail_url, file_url, next_video, subtitles} : Props) {

    const video = useRef<HTMLVideoElement>(null);
    const volume = JSON.parse(localStorage.getItem('volume') as string) || {value: 0.5, muted: false};
    const timeCode = new URLSearchParams(window.location.search).get('t') ?? false;

    const {mutate: view} = useMutation({
        mutationFn: () => viewVideo(video_id),
        mutationKey: ['videos.view', video_id],
    });

    const hasViewed = useRef(false);

    const handleVolumeChange = (event: any) => {
        localStorage.setItem("volume", JSON.stringify({
            value: event.target.volume.toFixed(2),
            muted: event.target.muted
        }));
    };

    const handleVideoEnded = (event: any) => {
        const autoplayNextVideo = JSON.parse(localStorage.getItem(AUTOPLAY_NEXT_VIDEO_KEY) || 'false');
        if(autoplayNextVideo && next_video) {
            window.location.replace(next_video);
        }
    };

    const handleLoaded = (event: any) => {
        video.current!.volume = volume.value;
        video.current!.muted = volume.muted;

        if (timeCode) {
            video.current!.currentTime = parseInt(timeCode);
        }
    };

    const handleTimeUpdate = (event: any) => {

        const time = event.target.currentTime;

        if(time > VIEW_COUNT_DURATION && !hasViewed.current) {
            hasViewed.current = true;
            view();
            video.current?.removeEventListener('timeupdate', handleTimeUpdate);
        }
    };

    useEffect(() => {

        const autoplayVideo = JSON.parse(localStorage.getItem(AUTOPLAY_VIDEO_KEY) || 'true');

        if (autoplayVideo) {
            video.current!.autoplay = true;
        }

        video.current?.addEventListener('loadedmetadata', handleLoaded);
        video.current?.addEventListener('timeupdate', handleTimeUpdate);
        video.current?.addEventListener('volumechange', handleVolumeChange);
        video.current?.addEventListener('ended', handleVideoEnded);

        return () => {
            video.current?.removeEventListener('loadedmetadata', handleLoaded);
            video.current?.removeEventListener('timeupdate', handleTimeUpdate);
            video.current?.removeEventListener('volumechange', handleVolumeChange);
            video.current?.removeEventListener('ended', handleVideoEnded);
        }

    }, [])

    return (
        <>
            <video
                ref={video}
                controls
                className="w-100 border border-1 rounded h-100"
                controlsList="nodownload"
                poster={thumbnail_url}
                onContextMenu={(e: any) => e.preventDefault()}
                playsInline
            >
                <source src={file_url} type="video/mp4"/>
                {
                    subtitles && JSON.parse(subtitles).map((subtitle: SubtitleType) => (
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
        </>

    )
}

export function Player(props: Props) {

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
