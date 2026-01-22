import {useTranslation} from "react-i18next";
import {clsx} from "clsx";

type Props = {
    small?: boolean
}
export function Loader ({small = false}: Props) {

    const { t } = useTranslation();

    return (
        <div class={clsx('spinner-border text-primary', small && 'spinner-border-sm')} role="status">
            <span class="visually-hidden">{t('Loading...')}</span>
        </div>
    )
}
