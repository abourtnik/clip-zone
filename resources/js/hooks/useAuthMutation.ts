
import {useMutation, UseMutationOptions} from "@tanstack/react-query";
import {show as showToast} from "../functions/toast";

type Props = UseMutationOptions & { authError: string }

declare global {
    interface Window { USER: {id: number, avatar: string} | null }
}

export function useAuthMutation(options: Props) {

    const isAuthenticated = !!window.USER;

    return useMutation({
        ...options,
        mutationFn: (args) => {
            if (!isAuthenticated) {
                showToast(options.authError);
                return Promise.reject()
            }

            if (options.mutationFn) {
                return options.mutationFn(args)
            }

            return Promise.resolve()
        },
        onError: (error) => {
            showToast(error.message ?? 'Whoops! An error occurred. Please try again later.');
        }
    });
}
