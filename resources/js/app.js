import '../sass/app.scss';
import '@fortawesome/fontawesome-free/js/all.js';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle';

import Alpine from 'alpinejs'

import './components/index.js'

import TomSelect from "tom-select";

window.Pusher = Pusher;
window.bootstrap = bootstrap;

window.Alpine = Alpine

Alpine.start()

const popovers = [...document.querySelectorAll('[data-bs-toggle="popover"]')]
popovers.map(element => new bootstrap.Popover(element))

const tooltips = [...document.querySelectorAll('[data-bs-toggle="tooltip"]')];
tooltips.map(element => new bootstrap.Tooltip(element))

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

document.querySelectorAll('.select-multiple').forEach((el)=>{
    new TomSelect(el,{
        plugins: {
            remove_button:{
                title:'Remove this item',
            }
        }
    });
});

if (document.querySelector('meta[name="user_id"]')?.getAttribute('content')) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true
    });

    window.User = {
        id: document.querySelector('meta[name="user_id"]').getAttribute('content')
    }

    window.Echo.private('App.Models.User.' + User.id).notification(notification => {
        console.log(notification);
    });
}



