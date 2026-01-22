import '../sass/app.scss';

// Libs
import * as bootstrap from 'bootstrap'
import Alpine from 'alpinejs'
import TomSelect from "tom-select";
import {TomOption, TomInput} from "tom-select/dist/cjs/types/core";
import Cropper from 'cropperjs';
import Sortable from 'sortablejs';
import Echo from "laravel-echo";
import Pusher from 'pusher-js';
import Chart from 'chart.js/auto';

// LANG
import './lang/config';

// Components
import './components/index'

window.bootstrap = bootstrap;
window.Alpine = Alpine
window.Cropper = Cropper
window.Sortable = Sortable
window.Pusher = Pusher;
window.Chart = Chart;


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

    let options : {content? : string, html? : boolean} = {};

    if (element.hasAttribute('data-bs-content-id')) {
        options.content = document.getElementById(element.getAttribute('data-bs-content-id')!)!.innerHTML
        options.html = true;
    }

    return new bootstrap.Popover(element, options)
});

document.querySelectorAll('.select-multiple').forEach((element)=>{
    new TomSelect(element as TomInput, {
        render: {
            option: function(data: TomOption, escape: Function) {
                return '<div class="text-body">' + escape(data.text) + '</div>';
            },
            item: function(data: TomOption, escape: Function ) {
                return '<div class="bg-body-secondary text-body border border-1">' + escape(data.text) + '</div>';
            },
            no_results:function(data: TomOption,escape: Function){
                return '<div class="no-results">No results found for "'+escape(data.input)+'"</div>';
            },
        },
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

    const target = e.currentTarget as HTMLElement;

    const url = target.dataset.url as string;
    const method = target.dataset.method ?? 'GET';
    const body = target.dataset.body ?? null;

    await fetch(url, {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')!.getAttribute('content') as string
        },
        method: method,
        credentials: 'include',
        body : body
    });

}))
