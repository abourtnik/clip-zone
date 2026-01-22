import {useTranslation} from "react-i18next";

type Props = {
    refetch: Function
    error: Error
}

export function ApiError ({refetch, error}: Props) {

    const { t } = useTranslation();

    return (
        <div className="border border-1 bg-light text-center p-3">
            <i className="fa-solid fa-triangle-exclamation fa-3x"></i>
            <h3 className="h5 my-3 fw-normal">{t('Something went wrong')}</h3>
            <p className="text-muted">{error.message ?? t('Unknown error')}</p>
            <button className="btn btn-primary rounded-5 text-uppercase btn-sm" onClick={() => refetch()}>
                {t('Try again')}
            </button>
        </div>
    )
}
