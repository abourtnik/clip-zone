import '../sass/app.scss';
import '@fortawesome/fontawesome-free/js/all.js';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min';

import Alpine from 'alpinejs'

import { Toast } from 'bootstrap'

import './components/index.js'

window.Pusher = Pusher;
window.bootstrap = bootstrap;

window.Alpine = Alpine

Alpine.start()

const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

/*
window.Echo.private('App.Models.User.' + User.id).notification(notification => {
 const notifications_count = document.getElementById('notifications_count');
 notifications_count.innerText = parseInt(notifications_count.innerText) + 1 ;

 document.querySelector('.toast-container').innerHTML += notification.toast
 const toast = new Toast(document.getElementById('toast-' + notification.toast_id));
 toast.show();
});
*/


