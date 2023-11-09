import { useState, useEffect } from 'preact/hooks';

export default function Ad ({duration}) {

    console.log('render')

    const [counter, setCounter] = useState(duration);

    useEffect(() => {
        const timer = counter > 0 && setInterval(() => setCounter(counter - 1), 1000);
        return () => clearInterval(timer);
    }, [counter]);

    return (
        <div className="position-relative">
            <small className="bg-warning bg-opacity-100 position-absolute top-0 left-0 p-2 m-2 text-white fw-bold rounded text-center" style="z-index: 1">
                Ads • 1 of 2 • 0:{counter}
            </small>
            <a href="#" className="bg-dark bg-opacity-50 position-absolute d-flex align-items-center gap-3 bottom-0 left-0 p-2 m-2 text-white fw-bold rounded text-decoration-none" style="z-index: 1">
                <img className="rounded-circle" src="/images/default_men.png" alt="" style="width: 48px;height: 48px;"/>
                <div className="d-flex flex-column gap-1">
                    <div>Kyle & Sallabgs</div>
                    <div>www.krys.com</div>
                </div>
            </a>
            {
                counter === 0 ?
                    <button className="btn btn-primary rounded position-absolute bottom-0 right-0 p-1 m-2 text-white fw-bold d-flex align-items-center gap-2" style="z-index: 1">
                        <span>Skip Ad</span>
                        <i className="fa-solid fa-forward-step"></i>
                    </button> :
                    <div className={'bg-dark d-flex position-absolute bottom-0 right-0 ps-2 m-2 text-white fw-bold d-flex align-items-center gap-2'} style="z-index: 1" >
                        <span>{counter}</span>
                        <img src="http://localhost:8080/video/thumbnail/c4a23881-1cb9-483f-b617-905a3895e6de" alt="" style="height: 41px;width: 73px;"/>
                    </div>
            }
            <div className="ratio ratio-16x9 position-relative">
                <video className="w-100 border border-2" controlsList="nodownload" autoPlay>
                    <source src="http://localhost:8080/video/file/vfbf4Vh1BmKqpPmfa4WGr98IM5aJREarYLtZ1nSZ.mp4" type="video/webm"/>
                </video>
            </div>
        </div>
    )
}
