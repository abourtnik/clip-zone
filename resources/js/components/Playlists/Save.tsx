import {Modal} from "react-bootstrap";
import {useState} from "preact/hooks";
import {InfiniteData, QueryClient, QueryClientProvider, useIsMutating, useQueryClient} from "@tanstack/react-query";
import {getMyPlaylists, savePlaylist} from "@/api/clipzone";
import {Fragment} from "preact";
import {PlaylistSaveDataSchema, PlaylistSaveData, PlaylistType, Paginator} from "@/types";
import {CreatePlaylist} from "@/components/Playlists/CreatePlaylist";
import {useCursorQuery, useErrorMutation} from "@/hooks";
import {produce} from "immer";
import {useTranslation} from "react-i18next";

type Props = {
    video: number
}

export function Main ({video}: Props) {

    const [open, setOpen] = useState(false);
    const [openCreate, setOpenCreate] = useState(false);

    const isSaving = useIsMutating({
        mutationKey: ['playlist.save'],
    });

    const { t } = useTranslation();

    const {
        data: playlists,
        isLoading,
        isError,
        refetch,
        isFetchingNextPage,
        hasNextPage,
        ref,
    } = useCursorQuery({
        queryKey: ['save'],
        queryFn: ({pageParam}) => getMyPlaylists(video, pageParam),
        enabled: open,
        staleTime: Infinity,
    });

    return (
        <>
            <button
                type={'button'}
                className="btn bg-light-dark btn-sm rounded-4 px-4 d-flex align-items-center gap-1"
                title="Save video"
                onClick={() => setOpen(true)}
            >
                <i class="fa-regular fa-bookmark"></i>&nbsp;
                {t('Save')}
            </button>
            <Modal show={open} onHide={() => setOpen(false)}>
                <Modal.Header closeButton>
                    <h5 className="modal-title">{t('Save Video to ...')}</h5>
                </Modal.Header>
                <Modal.Body className={'overflow-y-auto'} style={{'max-height': '500px'}}>
                    {
                        isLoading &&
                        <div className={'alert alert-primary d-flex align-items-center gap-3 mb-0'}>
                            <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span>{t('Loading your playlists ...')}</span>
                        </div>
                    }
                    {
                        isError &&
                        <div class="border border-1 bg-light text-center p-3">
                            <i class="fa-solid fa-triangle-exclamation fa-3x"></i>
                            <h3 class="h5 my-3 fw-normal">{t('Something went wrong !')}</h3>
                            <p class="text-muted">{t('If the issue persists please contact us.')}</p>
                            <button className="btn btn-primary rounded-5 text-uppercase btn-sm" onClick={() => refetch()}>
                                {t('Try again')}
                            </button>
                        </div>
                    }
                    {
                        playlists && (
                            playlists?.pages.flatMap((page => page.data)).length > 0 ?
                                <form id={'playlists-from'}>
                                    {playlists.pages.map((group, i) => (
                                        <Fragment key={i}>
                                            {group.data.map((playlist) => <Playlist key={playlist.id} playlist={playlist} video={video}/>)}
                                        </Fragment>
                                    ))}
                                </form>
                                :
                                <div className="d-flex flex-column justify-content-center align-items-center p-5">
                                    <i className="fa-solid fa-bell-slash fa-2x"></i>
                                    <h5 className="mt-3 fs-6">{t('Your don\'t have playlists yet')}</h5>
                                    <p className="text-muted text-center text-sm">{t('Create your first playlist to start saving videos')}</p>
                                </div>
                        )
                    }
                    {
                        isFetchingNextPage &&
                        <div className={'d-flex justify-content-center align-items-center py-3'}>
                            <div class="spinner-border text-primary spinner-border-sm" role="status">
                                <span class="visually-hidden">{t('Loading...')}</span>
                            </div>
                        </div>
                    }
                    {hasNextPage && <span ref={ref}></span>}
                </Modal.Body>
                <Modal.Footer>
                    <div className="d-flex justify-content-between w-100 align-items-center">
                        <button onClick={() => setOpenCreate(v => !v)} className="btn btn-success btn-sm">
                            <i className="fa-solid fa-plus"></i>&nbsp;
                            {t('Create new playlist')}
                        </button>
                        {
                            (isSaving > 0) &&
                            <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        }
                    </div>
                    <CreatePlaylist open={openCreate} setOpen={setOpenCreate}/>
                </Modal.Footer>
            </Modal>
        </>
    )
}

type PlaylistProps = {
    playlist: PlaylistType
    video: number,
}

function Playlist ({playlist, video} : PlaylistProps) {

    const queryClient = useQueryClient();

    const {mutate} = useErrorMutation({
        mutationFn: (data: PlaylistSaveData) => savePlaylist(data),
        mutationKey: ['playlist.save', playlist.uuid],
        onMutate: () => {
            queryClient.setQueryData(['save'], (oldData: InfiniteData<Paginator<PlaylistType>> | undefined) => {
                    if (!oldData) return undefined
                    return produce(oldData, draft => {
                        draft.pages.forEach(page => {
                            page.data.forEach(p => {
                                if (p.id === playlist.id) {
                                    p.has_video = !p.has_video;
                                }
                            });
                        });
                    });
                }
            )
        },
    });


    const handleChange = (event: any) => {

        const data = {
            playlist_id: playlist.id,
            video_id: video,
        };

        mutate(PlaylistSaveDataSchema.parse(data));
    }

    return (
        <div className="form-check mb-3">
            <div className="d-flex align-items-center gap-3">
                <div>
                    <input
                        className="form-check-input"
                        name={'playlist-' + playlist.id}
                        type="checkbox"
                        id={'playlist-' + playlist.id}
                        checked={playlist.has_video}
                        onChange={handleChange}
                    />
                    <label className="form-check-label text-break" htmlFor={'playlist-' + playlist.id}>
                        {playlist.title}
                    </label>
                </div>
                <i className={'fa-solid fa-' + playlist.icon}></i>&nbsp;
            </div>
        </div>
    )
}

export function Save(props: Props) {

    const queryClient = new QueryClient({
        defaultOptions: {
            queries: {
                retry: false,
            }
        }
    });

    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props}/>
        </QueryClientProvider>
    )
}
