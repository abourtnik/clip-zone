import { useReducer } from 'preact/hooks';
import {useTranslation} from "react-i18next";
import numeral from 'numeral'
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";
import {useErrorMutation} from "@/hooks/useErrorMutation";
import {interact} from "@/api/clipzone";

type Props = {
    model: 'App\\Models\\Video' | 'App\\Models\\Comment',
    target: number,
    count: string,
    active: string,
    showCount?: boolean
}

interface State {
    liked: boolean;
    disliked: boolean;
    counterLike: number;
    counterDislike: number;
}

type Action = 'LIKE' | 'DISLIKE';

const reducer = (state: State, action: Action) : State => {
    switch (action) {
        case 'LIKE':
            return {
                ...state,
                liked: !state.liked,
                disliked : false,
                counterLike: state.liked ? state.counterLike - 1 : state.counterLike + 1,
                counterDislike: state.disliked ? state.counterDislike - 1 : state.counterDislike
            }
        case 'DISLIKE':
            return {
                ...state,
                disliked: !state.disliked,
                liked : false,
                counterDislike: state.disliked ? state.counterDislike - 1 : state.counterDislike + 1,
                counterLike: state.liked ? state.counterLike - 1 : state.counterLike
            }
        default:
            return state;
    }
}

function Main ({model, target, count, active, showCount = true} : Props) {

    const { t } = useTranslation();

    const {like, dislike}: {like: boolean, dislike: boolean} = JSON.parse(active)
    const {likes_count, dislikes_count}: {likes_count: number, dislikes_count: number} = JSON.parse(count)

    const {mutate} = useErrorMutation({
        mutationFn: (type: 'like' | 'dislike') => interact(type, target, model),
        mutationKey: ['interaction', model, target],
    })

    const [state, dispatch] = useReducer(reducer, {
        liked: like,
        disliked : dislike,
        counterLike : likes_count,
        counterDislike : dislikes_count
    })

    const handleClick = async (type: Action) => {
        dispatch(type)
        mutate(type.toLocaleLowerCase() as 'like' | 'dislike')
    }

    return (
        <div className={'d-flex justify-content-between bg-light-dark rounded-4'}>
            <button
                onClick={() => handleClick('LIKE')}
                className={'hover-grey btn btn-sm border border-0 px-3 rounded-5 rounded-end ' + (state.liked ? 'text-success' : 'text-black') + (!showCount || state.counterLike === 0 ? ' py-2' : '')}
                data-bs-toggle="tooltip"
                data-bs-title={t("I like this")}
                data-bs-placement="bottom"
                data-bs-trigger="hover"
            >
                <div className={'d-flex gap-1 align-items-center'}>
                    {state.liked ? <i className="fa-solid fa-thumbs-up"></i> : <i className="fa-regular fa-thumbs-up"></i>}
                    { (showCount && state.counterLike > 0) && <span className={'ml-1'}>{numeral(state.counterLike).format('0[.]0a')}</span>}
                </div>
            </button>
            <div className="vr h-75 my-auto"></div>
            <button
                onClick={() => handleClick('DISLIKE')}
                className={'hover-grey btn btn-sm border border-0 px-3 rounded-5 rounded-start ' + (state.disliked ? 'text-danger' : 'text-black')}
                data-bs-toggle="tooltip"
                data-bs-title={t("I dislike this")}
                data-bs-placement="bottom"
                data-bs-trigger="hover"
            >
                <div className={'d-flex gap-1  align-items-center'}>
                    {state.disliked ? <i className="fa-solid fa-thumbs-down"></i> : <i className="fa-regular fa-thumbs-down"></i>}
                    { (showCount && state.counterDislike > 0) && <span className={'ml-1'}>{numeral(state.counterDislike).format('0[.]0a')}</span>}
                </div>
            </button>
        </div>
    )
}

export function Interaction(props: Props) {

    const queryClient = new QueryClient({
        defaultOptions: {
            queries: {
                retry: false,
            }
        }
    });

    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props} />
        </QueryClientProvider>
    )
}
