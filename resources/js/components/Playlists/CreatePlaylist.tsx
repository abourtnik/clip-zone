import {Button} from "@/components/Commons";
import {useRef, useState} from "preact/hooks";
import {InfiniteData, useQueryClient} from "@tanstack/react-query";
import {useErrorMutation} from "@/hooks";
import {PlaylistCreateDataSchema, Paginator, PlaylistType, PlaylistCreateData, PlaylistStatus} from "@/types";
import {createPlaylist} from "@/api/clipzone";
import {produce} from "immer";
import {useTranslation} from "react-i18next";
import {enumToArray} from "@/functions/string";

type Props = {
    open: boolean
    setOpen: (value: boolean) => void
}

export function CreatePlaylist ({open, setOpen} : Props) {

    const [count, setCount] = useState<number>(0);

    const form = useRef<HTMLFormElement>(null);

    const queryClient = useQueryClient();

    const { t } = useTranslation();

    const {mutate, isPending} = useErrorMutation({
        mutationFn: (data: PlaylistCreateData) => createPlaylist(data),
        mutationKey: ['playlists.create'],
        onSuccess: (playlist) => {
            queryClient.setQueryData(['save'], (oldData: InfiniteData<Paginator<PlaylistType>> | undefined) => {
                    if (!oldData) return undefined
                    return produce(oldData, draft => {
                        draft.pages[draft.pages.length - 1].data.push(playlist);
                    });
                }
            )
            form.current!.reset();
            setOpen(false);
            setCount(0);
        }
    })

    const handleSubmit = async (event: any) => {

        event.preventDefault();

        const formData = new FormData(event.target);

        const data = Object.fromEntries(formData.entries());

        mutate(PlaylistCreateDataSchema.parse(data))
    }

    return (
        <>
            {
                open &&
                <form ref={form} onSubmit={handleSubmit} className="card card-body bg-light">
                    <div className="mb-3">
                        <label htmlFor="title" className="form-label">{t('Title')}</label>
                        <input
                            type="text"
                            className="form-control"
                            id="title"
                            name="title"
                            required
                            minLength={1}
                            maxLength={150}
                            onChange={(e: any) => setCount(e.currentTarget.value.length)}
                        />
                        <div className="form-text">
                            {count} / 150
                        </div>
                    </div>
                    <div className="mb-3">
                        <label htmlFor="status" className="form-label">{t('Visibility')}</label>
                        <select className="form-control" name="status" id="status" required>
                            {
                                enumToArray(PlaylistStatus).map(({value, label}) => (
                                    <option value={value}>{t(label)}</option>
                                ))
                            }
                        </select>
                    </div>
                    <div className="d-flex gap-2 justify-content-end">
                        <button onClick={() => setOpen(false)} type="button" className="btn btn-secondary btn-sm">
                            {t('Cancel')}
                        </button>
                        <Button type={'submit'} loading={isPending} color={'success'}>
                            {t('Create')}
                        </Button>
                    </div>
                </form>
            }
        </>
    )
}
