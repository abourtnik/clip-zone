import { useState } from 'preact/hooks';
import {useTranslation} from "react-i18next";
import {subscribe} from "@/api/clipzone";
import {QueryClient, QueryClientProvider} from '@tanstack/react-query'
import {useErrorMutation} from "@/hooks/useErrorMutation";
import {clsx} from "clsx";

type Props = {
    isSubscribe?: boolean,
    user: number,
    size?: string | null,
}

function Main ({isSubscribe = true, user, size = null} : Props) {

    const {isPending, mutate} = useErrorMutation({
        mutationKey: ['subscribe', user],
        mutationFn: () => subscribe(user),
        onSuccess: () => setSubscribed(v => !v),
    })

    const { t } = useTranslation();

    const [subscribed, setSubscribed] = useState<boolean>(isSubscribe);

    return (
        <button
            onClick={() => mutate()}
            className={clsx("btn rounded-4 px-3", {
                "bg-light-dark text-dark": subscribed,
                "btn-dark": !subscribed,
                ['btn-' + size]: size,
            })}
            disabled={isPending}
        >
            {isPending && <span className="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>}
            <span className={'text-sm'}>{subscribed ? t('Subscribed') : t('Subscribe')}</span>
        </button>
    )
}

export function Subscribe (props : Props) {

    const queryClient = new QueryClient();

    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props} />
        </QueryClientProvider>
    )
}
