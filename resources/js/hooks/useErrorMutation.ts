
import {useMutation, UseMutationOptions, UseMutationResult} from "@tanstack/react-query";
import {show as showToast} from "../functions/toast";
export function useErrorMutation<TData = unknown, TError = unknown, TVariables = void, TContext = unknown>(options: UseMutationOptions<TData, TError, TVariables, TContext>) : UseMutationResult<TData, TError, TVariables, TContext> {
    return useMutation({
        ...options,
        onError: (error) => {
            showToast((error as Error)?.message ?? 'Whoops! An error occurred. Please try again later.');
        }
    });
}
