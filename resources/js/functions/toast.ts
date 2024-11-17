import 'bootstrap';
import { Toast } from 'bootstrap';

export function show(message: string) {

    const toast_message = document.getElementById('toast-message') as HTMLElement
    const toastElement = document.getElementById('toast') as HTMLDivElement;

    toast_message.innerText = message;
    new Toast(toastElement).show()
}
