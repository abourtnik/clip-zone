import {HistoryType, VideoType} from "@/types";
import {useErrorMutation} from "@/hooks";
import {removeFromHistory} from "@/api/clipzone";
import {useQueryClient} from "@tanstack/react-query";
import {produce} from "immer";
import {Video} from "@/components/Videos";
import {useTranslation} from "react-i18next";

type Props = {
    view: {
        id: number;
        video: VideoType;
    }
}

export default function HistoryItem ({view} : Props) {

    const queryClient = useQueryClient();

    const { t } = useTranslation();

    const {mutate: remove}= useErrorMutation({
        mutationFn: () => removeFromHistory(view.id),
        mutationKey: ['history.remove'],
        onMutate: () => {
            queryClient.setQueryData(['history'], (oldData: HistoryType | undefined) => {
                if (!oldData) return undefined;
                return produce(oldData, draft => {
                    draft.data.forEach(item => {
                        item.views = item.views.filter(v => v.id !== view.id);
                    });
                });
            });

        }
    });

    const actions = [
        {
            label: t('Remove from history'),
            icon: 'trash',
            action: remove
        }
    ];

    return (
        <Video video={view.video} actions={actions}/>
    )
}
