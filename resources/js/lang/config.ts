import i18n from 'i18next';
import ICU from 'i18next-icu';
import { initReactI18next } from 'react-i18next';

import fr from "./fr";
import en from "./en";

i18n
    .use(initReactI18next)
    .use(ICU)
    .init({
        resources: {
            en: {
                translation: en
            },
            fr: {
                translation: fr
            }
        },
        fallbackLng: 'en',
        lng: window.LANG,
        interpolation: {
            escapeValue: false
        }
});

export default i18n;
