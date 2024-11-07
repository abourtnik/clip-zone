import '../sass/app.scss';

// Libs
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle';
import Alpine from 'alpinejs'
import TomSelect from "tom-select";
import 'htmx.org';
import Cropper from 'cropperjs';
import Sortable from 'sortablejs';
import Echo from "laravel-echo";
import Pusher from 'pusher-js';

// LANG
import './lang/config';

// Components
import './components/index.js'

window.bootstrap = bootstrap;
window.Alpine = Alpine
window.Cropper = Cropper
window.Sortable = Sortable
window.Pusher = Pusher;


// Laravel Echo

window.Echo = new Echo({
    broadcaster: 'pusher',
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_WEBSOCKETS_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    forceTLS: false,
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

Alpine.start()

const tooltips = [...document.querySelectorAll('[data-bs-toggle="tooltip"]')]
tooltips.map(element => new bootstrap.Tooltip(element))

const popovers = [...document.querySelectorAll('[data-bs-toggle="popover"]')]
popovers.map(element => {

    let options = {};

    if (element.hasAttribute('data-bs-content-id')) {
        options.content = document.getElementById(element.getAttribute('data-bs-content-id')).innerHTML
        options.html = true;
    }

    return new bootstrap.Popover(element, options)
});

document.querySelectorAll('.select-multiple').forEach((element)=>{
    new TomSelect(element,{
        plugins: {
            remove_button:{
                title:'Remove this item',
            }
        }
    });
});

// Ajax Button

const ajaxButtons = [...document.querySelectorAll('.ajax-button')];
ajaxButtons.map(element => element.addEventListener('click', async e => {

    const url = e.currentTarget.dataset.url;
    const method = e.currentTarget.dataset.method ?? 'GET';
    const body = e.currentTarget.dataset.body ?? null;

    await fetch(url, {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        method: method,
        credentials: 'include',
        body : body
    });

}))

// THEME

const themesSwitchers = [...document.getElementsByClassName('theme-switcher')];

const theme = localStorage.getItem("theme");
if (theme && theme === 'dark') {
    if (themesSwitchers) {
        themesSwitchers.forEach(switcher => switcher.checked = true)
    }
    document.documentElement.setAttribute('data-bs-theme', 'dark')
}

themesSwitchers.forEach(switcher =>  {
    switcher.addEventListener('change', e => {
        const theme = e.currentTarget.checked ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', theme)
        localStorage.setItem("theme", theme);
        themesSwitchers.filter(switcher => switcher.id !== e.currentTarget.id).forEach(switcher => switcher.checked = e.currentTarget.checked)
    })
})

// AUTOPLAY

const autoplaySwitchers = [...document.getElementsByClassName('autoplay-switcher')];

const autoplay = JSON.parse(localStorage.getItem("autoplay-video") || true);
if (autoplay) {
    if (autoplaySwitchers) {
        autoplaySwitchers.forEach(switcher => switcher.checked = true)
    }
}

autoplaySwitchers.forEach(switcher =>  {
    switcher.addEventListener('change', e => {
        localStorage.setItem("autoplay-video", e.currentTarget.checked);
        autoplaySwitchers.filter(switcher => switcher.id !== e.currentTarget.id).forEach(switcher => switcher.checked = e.currentTarget.checked)
    })
})
