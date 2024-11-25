export {};

declare global {
    interface Window {
        USER: { id: number, avatar: string } | null,
        resumable: Resumable
        PRIVATE_CHANNEL: any;
    }
}
