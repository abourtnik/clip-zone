import { useState} from 'preact/hooks';

export default function ImageLoaded ({source, title, imgclass}) {

    const [loading, setLoading] = useState(true);

    const imageLoad = () => {
        setLoading(false);
    }

    return (
        <>
        {
            loading &&
            <img className={imgclass} src={'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAs4AAAGUCAYAAAAlEaQgAAAAAXNSR0IArs4c6QAAF2hJREFUeF7t1qERhEAQRUHGkn9mgCUOFFwERz1Pr/5mu0a8OY7jue978QgQIECAAAECBAgQ+C8w27Y9gAgQIECAAAECBAgQeBcQzi6EAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIAsI5IJkQIECAAAECBAgQEM5ugAABAgQIECBAgEAQEM4ByYQAAQIECBAgQICAcHYDBAgQIECAAAECBIKAcA5IJgQIECBAgAABAgSEsxsgQIAAAQIECBAgEASEc0AyIUCAAAECBAgQICCc3QABAgQIECBAgACBICCcA5IJAQIECBAgQIAAAeHsBggQIECAAAECBAgEAeEckEwIECBAgAABAgQICGc3QIAAAQIECBAgQCAICOeAZEKAAAECBAgQIEBAOLsBAgQIECBAgAABAkFAOAckEwIECBAgQIAAAQLC2Q0QIECAAAECBAgQCALCOSCZECBAgAABAgQIEBDOboAAAQIECBAgQIBAEBDOAcmEAAECBAgQIECAgHB2AwQIECBAgAABAgSCgHAOSCYECBAgQIAAAQIEhLMbIECAAAECBAgQIBAEhHNAMiFAgAABAgQIECAgnN0AAQIECBAgQIAAgSAgnAOSCQECBAgQIECAAAHh7AYIECBAgAABAgQIBAHhHJBMCBAgQIAAAQIECAhnN0CAAAECBAgQIEAgCAjngGRCgAABAgQIECBAQDi7AQIECBAgQIAAAQJBQDgHJBMCBAgQIECAAAECwtkNECBAgAABAgQIEAgCwjkgmRAgQIAAAQIECBAQzm6AAAECBAgQIECAQBAQzgHJhAABAgQIECBAgIBwdgMECBAgQIAAAQIEgoBwDkgmBAgQIECAAAECBISzGyBAgAABAgQIECAQBIRzQDIhQIAAAQIECBAgIJzdAAECBAgQIECAAIEgIJwDkgkBAgQIECBAgAAB4ewGCBAgQIAAAQIECAQB4RyQTAgQIECAAAECBAgIZzdAgAABAgQIECBAIAgI54BkQoAAAQIECBAgQEA4uwECBAgQIECAAAECQUA4ByQTAgQIECBAgAABAsLZDRAgQIAAAQIECBAIArPv+xN2JgQIECBAgAABAgQ+LTDneV7rui4z82kInydAgAABAgQIECDwJqCW3QcBAgQIECBAgACBIPADYwZ7BgQTqgYAAAAASUVORK5CYII='} alt={'default '}/>

        }
            <img className={imgclass + (loading ? ' d-none' : ' d-block')} src={source} alt={title} onLoad={imageLoad}/>
        </>
    )
}