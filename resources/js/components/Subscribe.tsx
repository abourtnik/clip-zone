import { useState } from 'preact/hooks';
import {useTranslation} from "react-i18next";
import {subscribe} from "@/api/clipzone";
import {QueryClient, QueryClientProvider,} from '@tanstack/react-query'
import {useAuthMutation} from "@/hooks/useAuthMutation";

type Props = {
    isSubscribe?: boolean,
    user: number,
    size?: string | null,
}

function Main ({isSubscribe = true, user, size = null} : Props) {

    const {isPending, mutate} = useAuthMutation({
        mutationKey: ['subscribe', user],
        mutationFn: () => subscribe(user),
        onSuccess: () => setSubscribed(v => !v),
        authError: 'Sign in to subscribe to this channel'
    })

    const { t } = useTranslation();

    const [subscribed, setSubscribed] = useState<boolean>(isSubscribe);

    const className = (subscribed ? 'btn-secondary text-white' : 'btn-danger') + (size ? ' btn-' + size : '');

    return (
        <button onClick={() => mutate()} className={'btn rounded-4 px-3 ' + className} disabled={isPending}>
            {isPending && <span className="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>}
            {subscribed ? t('Subscribed') : t('Subscribe')}
        </button>
    )
}

export default function Subscribe (props : Props) {

    const queryClient = new QueryClient();

    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props} />
        </QueryClientProvider>
    )
}
