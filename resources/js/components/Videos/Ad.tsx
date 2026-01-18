import {useState, useEffect} from "preact/hooks";
import {useTranslation} from "react-i18next";

type Props = {
    setAds: (ads: boolean) => void
}

const ADS_DURATION: number = 5;

export function Ad ({setAds} : Props) {

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
            <div className={'text-white text-center'}>
                <h3 className={'pb-2'}>{t('Your Ad Could Be Here')}</h3>
                <p>{t('Interested in featuring your video ?')}</p>
                <a className={'btn btn-primary btn-sm'} href={'/contact'}>{t('Contact us today')}</a>
            </div>
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
