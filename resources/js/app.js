import '../sass/app.scss';
import '@fortawesome/fontawesome-free/js/all.js';

// Libs
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle';
import Alpine from 'alpinejs'
import TomSelect from "tom-select";
import 'htmx.org';
import Cropper from 'cropperjs';

// Components
import './components/index.js'

window.bootstrap = bootstrap;
window.Alpine = Alpine
window.Cropper = Cropper

Alpine.start()

new bootstrap.Tooltip(document.body, {
    selector: '[data-bs-toggle="tooltip"]'
});

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
