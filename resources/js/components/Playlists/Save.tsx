import {Modal} from "react-bootstrap";
import {useEffect, useState} from "preact/hooks";
import {InfiniteData, QueryClient, QueryClientProvider, useInfiniteQuery, useQueryClient} from "@tanstack/react-query";
import {getUserPlaylists, savePlaylist} from "@/api/clipzone";
import {Fragment} from "preact";
import {PlaylistSaveDataSchema, PlaylistSaveData, PlaylistType, Paginator} from "@/types";
import {useInView} from "react-intersection-observer";
import {CreatePlaylist} from "@/components/Playlists/CreatePlaylist";
import {useErrorMutation} from "@/hooks";
import clsx from "clsx";
import {produce} from "immer";

type Props = {
    video: number
}

export function Main ({video}: Props) {

    const [open, setOpen] = useState(false);
    const [openCreate, setOpenCreate] = useState(false);
    const [saved, setSaved] = useState(false);

    const { ref, inView} = useInView();

    const {
        data: playlists,
        isLoading,
        isError,
        isFetchingNextPage,
        fetchNextPage,
        hasNextPage,
        refetch,
    } = useInfiniteQuery({
        queryKey: ['save'],
        queryFn: ({pageParam}) => getUserPlaylists(window.USER!.id, pageParam, video),
        initialPageParam: 1,
        enabled: open,
        staleTime: Infinity,
        getNextPageParam: (lastPage, allPages, lastPageParam) => {
            if (lastPage.meta.current_page === lastPage.meta.last_page) {
                return undefined
            }
            return lastPageParam + 1
        }
    });

    const {mutate, isPending} = useErrorMutation({
        mutationFn: (data: PlaylistSaveData) => savePlaylist(data),
        mutationKey: ['playlist.save'],
        onSuccess: () => {
            setSaved(true);
            setTimeout(() => setSaved(false), 3000)
        }
    })

    useEffect( () => {
        if (inView && !isFetchingNextPage && !isError) {
            fetchNextPage()
        }
    }, [inView]);

    const handleSubmit = (e: any) => {

        e.preventDefault();

        const data = {
            video_id : video,
            playlists : [ ...document.querySelectorAll('input[name="playlists[]"]') as NodeListOf<HTMLInputElement>].map(input  => ({
                'id': input.dataset.id,
                'checked': input.checked
            }))
        };

        mutate(PlaylistSaveDataSchema.parse(data));
    }

    return (
        <>
            <button
                type={'button'}
                className="btn bg-light-dark btn-sm rounded-4 px-4"
                title="Save video"
                onClick={() => setOpen(true)}
            >
                <i class="fa-regular fa-bookmark"></i>&nbsp;
                Save
            </button>
            <Modal show={open} onHide={() => setOpen(false)}>
                <Modal.Header closeButton>
                    <h5 className="modal-title">Save Video to ...</h5>
                </Modal.Header>
                <Modal.Body>
                    {
                        isLoading &&
                        <div className={'alert alert-primary d-flex align-items-center gap-3 mb-0'}>
                            <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span>Loading your playlists ...</span>
                        </div>
                    }
                    {
                        isError &&
                        <div class="border border-1 bg-light text-center p-3">
                            <i class="fa-solid fa-triangle-exclamation fa-3x"></i>
                            <h3 class="h5 my-3 fw-normal">Something went wrong !</h3>
                            <p class="text-muted">If the issue persists please contact us.</p>
                            <button className="btn btn-primary rounded-5 text-uppercase btn-sm" onClick={() => refetch()}>
                                Try again
                            </button>
                        </div>
                    }
                    {
                        playlists && (
                            playlists?.pages.flatMap((page => page.data)).length > 0 ?
                                <form id={'playlists-from'} onSubmit={handleSubmit}>
                                    {playlists.pages.map((group, i) => (
                                        <Fragment key={i}>
                                            {group.data.map((playlist) => <Playlist key={playlist.id}
                                                                                    playlist={playlist}/>)}
                                        </Fragment>
                                    ))}
                                </form>
                                :
                                <div className="d-flex flex-column justify-content-center align-items-center p-5">
                                    <i className="fa-solid fa-bell-slash fa-2x"></i>
                                    <h5 className="mt-3 fs-6">Your don't have playlists yet</h5>
                                    <p className="text-muted text-center text-sm">Create your first playlist to start saving videos</p>
                                </div>
                        )
                    }
                    {
                        isFetchingNextPage &&
                        <div className={'d-flex justify-content-center align-items-center py-3'}>
                            <div class="spinner-border text-primary spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    }
                    {hasNextPage && <span ref={ref}></span>}
                </Modal.Body>
                <Modal.Footer>
                    <div className="d-flex justify-content-between w-100 align-items-center">
                        <button onClick={() => setOpenCreate(v => !v)} className="btn btn-success btn-sm">
                            <i className="fa-solid fa-plus"></i>&nbsp;
                            Create new playlist
                        </button>
                        <button type={'submit'} form={'playlists-from'} className={clsx('btn', !saved && 'btn-primary', saved && 'btn-success')} disabled={isPending || saved}>
                            {isPending &&
                                <div className={'d-flex align-items-center gap-2'}>
                                    <span className="spinner-border spinner-border-sm" role="status"
                                          aria-hidden="true"></span>
                                    <span>Save</span>
                                </div>
                            }
                            {(!isPending && !saved) && <span>Save</span>}
                            {saved &&
                                <div className={'d-flex align-items-center gap-2'}>
                                    <i className="fa-solid fa-check"></i>
                                    <span>Saved</span>
                                </div>
                            }
                        </button>
                    </div>
                    <CreatePlaylist open={openCreate} setOpen={setOpenCreate}/>
                </Modal.Footer>
            </Modal>
        </>
    )
}

type PlaylistProps = {
    playlist: PlaylistType
}

function Playlist ({playlist} : PlaylistProps) {

    const queryClient = useQueryClient()

    const handleChange = (event: any) => {
        const checkbox = event.target as HTMLInputElement;
        const id = checkbox.dataset.id as string;

        queryClient.setQueryData(['save'], (oldData: InfiniteData<Paginator<PlaylistType>> | undefined) => {
                if (!oldData) return undefined
                return produce(oldData, draft => {
                    draft.pages.forEach(page => {
                        page.data.forEach(p => {
                            if (p.id === parseInt(id)) {
                                p.has_video = checkbox.checked;
                            }
                        });
                    });
                });
            }
        )
    }


    return (
        <div className="form-check mb-3">
            <div className="d-flex align-items-center gap-3">
                <div>
                    <input
                        className="form-check-input"
                        name="playlists[]"
                        type="checkbox"
                        value={playlist.id}
                        id={'playlist-' + playlist.id}
                        data-id={playlist.id}
                        checked={playlist.has_video}
                        onChange={handleChange}
                    />
                    <label className="form-check-label" htmlFor={'playlist-' + playlist.id}>
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
