import {QueryClient, QueryClientProvider, useQuery, useQueryClient} from "@tanstack/react-query";
import {getSubtitles} from "@/api/clipzone";
import {ApiError} from "@/components/Commons";
import {SubtitleType, SubtitleDraft} from "@/types";
import {useEffect} from 'preact/hooks';
import {Subtitle, SubtitleFormRow} from "@/components/Subtitles";
import {OverlayTrigger, Tooltip} from "react-bootstrap";
import {useTranslation} from "react-i18next";
import {useState} from "react";
import {produce} from "immer";

type Props = { video: string }

function Main ({video} : Props) {

    const {
        data: subtitles,
        isLoading,
        isError,
        refetch,
        error
    } = useQuery({
        queryKey: ['videos', video, 'subtitles'],
        queryFn: () => getSubtitles(video),
    });

    const { t } = useTranslation();

    const queryClient = useQueryClient();

    const [drafts, setDrafts] = useState<SubtitleDraft[]>([]);

    useEffect(() => {

        const queryKey = ['videos', video, 'subtitles'];

        const replaceSubtitle = (subtitle: SubtitleType) => {
            queryClient.setQueryData<{ data: SubtitleType[] }>(queryKey, (old) =>
                old &&
                produce(old, (draft) => {
                    const index = draft.data.findIndex((s) => s.id === subtitle.id);
                    if (index !== -1) draft.data[index] = subtitle;
                })
            );
        };

        window.PRIVATE_CHANNEL
            .listen('.subtitle.generated', replaceSubtitle)
            .listen('.subtitle.error', replaceSubtitle)

        return () => {
            window.PRIVATE_CHANNEL
                .stopListening('.subtitle.generated')
                .stopListening('.subtitle.error')
        }
    }, []);

   const addDraft = () =>
       setDrafts((drafts) => [
           { tempId: crypto.randomUUID(), video_id: video},
           ...drafts
        ]);

    const removeDraft = (draft: SubtitleDraft) =>
        setDrafts((drafts) => drafts.filter((d) => d.tempId !== draft.tempId));

   return (
       <div className="w-100">
           <div className="d-flex justify-content-start align-items-center mb-3">
               <button type={'button'} class={'btn btn-sm btn-success'} onClick={addDraft}>
                   <i className="fa-solid fa-plus"></i>
                   <span className="ms-2">{t('Add subtitle')}</span>
               </button>
           </div>
           <div>
               {
                   isLoading &&
                   <div className={'col-12 d-flex flex-column gap-2'}>
                       <div className={'placeholder-wave w-100 position-relative tw:h-14'}>
                           <span className="placeholder h-100 w-100"></span>
                       </div>
                       <div className={'placeholder-wave w-100 position-relative tw:h-14'}>
                           <span className="placeholder h-100 w-100"></span>
                       </div>
                   </div>
               }
               {isError && <ApiError refetch={refetch} error={error}/>}
               {
                   (subtitles && subtitles.data.length > 0 || drafts.length > 0) &&
                   <div className="overflow-auto">
                       <table className="table table-striped table-hover table-bordered align-middle">
                           <thead>
                               <tr>
                                   <th scope="col" className={'w-9'}></th>
                                   <th scope="col" className={'tw:min-w-[215px]'}>{t('Language')}</th>
                                   <th scope="col" className={'tw:min-w-[230px]'}>
                                       <div class={'d-flex gap-2 align-items-center'}>
                                           <span>{t('Name')}</span>
                                           <OverlayTrigger
                                               trigger={['hover', 'focus']}
                                               placement={'top'}
                                               rootClose={true}
                                               overlay={<Tooltip className={'bg-white'}>{t('Name which will be displayed on the video player')}</Tooltip>}
                                           >
                                               <i className="fa-solid fa-circle-info"></i>
                                           </OverlayTrigger>
                                       </div>
                                   </th>
                                   <th scope="col" className={'tw:min-w-[200px]'}>{t('Source')}</th>
                                   <th scope="col" className={'w-12'}>{t('Status')}</th>
                                   <th scope="col" className={'tw:min-w-[200px]'}>{t('Actions')}</th>
                               </tr>
                           </thead>
                           <tbody>
                                {drafts.map((draft) => <SubtitleFormRow key={draft.tempId} draft={draft} onCancel={() => removeDraft(draft)}/>)}
                                {subtitles && subtitles.data.map((subtitle) => <Subtitle key={subtitle.id} subtitle={subtitle}/>)}
                           </tbody>
                       </table>
                   </div>
               }
               {(subtitles?.data.length === 0 && drafts.length === 0) && (
                   <div class="my-3 text-center border rounded py-3 bg-light d-flex flex-column gap-3">
                       <i class="fa-solid fa-closed-captioning fa-2x"></i>
                       <h5 class="">{t('No subtitles')}</h5>
                   </div>
               )}
           </div>
       </div>
   )
}

export function SubtitlesList(props: Props) {

    const queryClient = new QueryClient({
        defaultOptions: {
            queries: {
                retry: false,
                refetchOnWindowFocus: false,
                refetchOnReconnect:false,
                refetchOnMount :false
            }
        }
    });

    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props}/>
        </QueryClientProvider>
    )
}
