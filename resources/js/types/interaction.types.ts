import {UserType} from "@/types";

export type InteractionType = {
    id: number,
    status: boolean,
    user: UserType,
    perform_at: Date,
}

export type InteractionsFilters = 'all' | 'up' | 'down';

export type InteractionsModel = 'App\\Models\\Video' | 'App\\Models\\Comment';
