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
popovers.map(element => new bootstrap.Popover(element))

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

const switchers = [...document.getElementsByClassName('theme-switcher')];

const theme = localStorage.getItem("theme");
if (theme && theme === 'dark') {
    if (switchers) {
        switchers.forEach(switcher => switcher.checked = true)
    }
    document.documentElement.setAttribute('data-bs-theme', 'dark')
}

switchers.forEach(switcher =>  {
    switcher.addEventListener('change', e => {
        const theme = e.currentTarget.checked ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', theme)
        localStorage.setItem("theme", theme);
        switchers.filter(switcher => switcher.id !== e.currentTarget.id).forEach(switcher => switcher.checked = e.currentTarget.checked)
    })
})
