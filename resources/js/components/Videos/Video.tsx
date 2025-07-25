import {useState} from 'preact/hooks';
import {useTranslation} from "react-i18next";
import moment from 'moment';
import {VideoType} from "@/types";
import {memo} from "preact/compat";
import numeral from "numeral";

type Props = {
    video : VideoType
}

export const Video = memo(({video} : Props) => {

    const { t } = useTranslation();

    const [loading, setLoading] = useState<boolean>(true);
    const [hover, setHover] = useState<boolean>(false);

    const imageLoad = () => {
        setLoading(false);
    }

    return (
        <article className="col px-0 px-sm-2">
            <div className="position-relative h-100">
                <a href={video.route}>
                    <div className="position-relative">
                        {
                            loading &&
                            <img className={'img-fluid rounded-4-sm w-100'} src={'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAs4AAAGUCAYAAAAlEaQgAAAAAXNSR0IArs4c6QAAF2hJREFUeF7t1qERhEAQRUHGkn9mgCUOFFwERz1Pr/5mu0a8OY7jue978QgQIECAAAECBAgQ+C8w27Y9gAgQIECAAAECBAgQeBcQzi6EAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIArPv+xN2JgQIECBAgAABAgQ+LTDneV7rui4z82kInydAgAABAgQIECDwJqCW3QcBAgQIECBAgACBIPADYwZ7BgQTqgYAAAAASUVORK5CYII='} alt={'default '}/>
                        }
                        <div className={'image-box rounded-4-sm position-relative overflow-hidden' + (hover && !loading ? ' hover' : '')}>
                            <img className={'img-fluid w-100 ' + (loading ? 'd-none' : 'd-block')} src={video.thumbnail} alt={video.title} onLoad={imageLoad}/>
                        </div>
                        <small
                            className="position-absolute p-1 text-white bg-dark fw-bold rounded"
                            style="font-size: 0.70rem;bottom: 8px;right: 6px;"
                        >
                            {video.formated_duration}
                        </small>
                    </div>
                    <span className={'position-absolute inset'} onMouseEnter={() => setHover(true)} onMouseLeave={() => setHover(false)}></span>
                </a>
                <div className="d-flex pt-2 px-3 px-sm-0">
                    <a href={video.user.route} style="height: 36px;" className="position-relative" title={video.user.username}>
                        <img className="rounded-circle" src={video.user.avatar} alt={video.user.username + ' avatar'} style={{width: '36px'}} />
                    </a>
                    <div className="ml-2">
                        <a href={video.route} className="fw-bolder text-decoration-none text-black d-block position-relative text-break" title={video.title}>{video.short_title}</a>
                        <a href={video.user.route} className="position-relative text-decoration-none">
                            <small>{video.user.username}</small>
                        </a>
                        <small className="text-muted d-block">{t( 'Views', { count: video.views, formatted: numeral(video.views).format('0.[0]a') } )} • {moment(video.publication_date).fromNow()}</small>
                    </div>
                </div>
            </div>
        </article>
    )
})
