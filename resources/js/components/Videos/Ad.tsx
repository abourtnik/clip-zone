import {useState, useEffect} from "preact/hooks";
import {useTranslation} from "react-i18next";

type Props = {
    setAds: (ads: boolean) => void
}

const ADS_DURATION: number = 5;

export function Ad ({setAds} : Props) {

    const appEnv = import.meta.env.VITE_APP_ENV;

    const [counter, setCounter] = useState<number>(ADS_DURATION);

    const { t } = useTranslation();

    useEffect(() => {

        if(counter === 0) {
            setAds(false);
            return;
        }

        const timer = setInterval(() => setCounter(counter - 1), 1000);
        return () => clearInterval(timer);
    }, [counter]);

    return (
        <div className={'w-100 h-100 d-flex justify-content-center align-items-center position-relative bg-dark text-white'}>
            {
                appEnv === 'production' &&
                <div className={'w-100 h-100'}>
                    <ins class="adsbygoogle"
                         style="display:block; width:100%; height: 100%"
                         data-ad-client="ca-pub-3386885268137177"
                         data-ad-slot="4529085098"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            }
            {
                appEnv === 'local' &&
                <div className={'text-white text-center'}>
                    <h3 className={'pb-2'}>{t('Your Ad Could Be Here')}</h3>
                    <p>{t('Do you want to display your ad before each video ?')}</p>
                    <a className={'btn btn-primary btn-sm'} href={'/contact'}>{t('Contact us today')}</a>
                </div>
            }
            <div className={'position-absolute bottom-5 right-5'}>
                {
                    counter !== 0 &&
                    <span>{counter}</span>
                }
            </div>
            <div className={'position-absolute bottom-5 left-5'}>{t('Ad')}</div>
        </div>
    )
}
