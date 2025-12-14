import { Alpine as AlpineType } from 'alpinejs'

export {};

declare global {
    interface Window {
        USER: { id: number, avatar: string } | null,
        resumable: Resumable
        PRIVATE_CHANNEL: any;
        LANG: string;
        Alpine: AlpineType
        Cropper: any
        Sortable: any
        Pusher : any
        Chart: any
        Echo : any
    }
}
