import {useState} from "preact/hooks";
import {QueryClient, QueryClientProvider, useMutation} from "@tanstack/react-query";
import {input} from "@/api/clipzone";
import {clsx} from "clsx";
import {InputHTMLAttributes, FormEvent} from "react";

type Props = {
    value: string,
    name: string,
    url: string,
} & InputHTMLAttributes;

type Data = { [key: string]: string };

function Main ({value, name, url, ...props} : Props) {

    const [edit, setEdit] = useState<boolean>(false);
    const [val, setVal] = useState<string>(value);

    const {mutate, isPending, isError, error} = useMutation({
        mutationFn: (data: Data) => input(url, data),
        mutationKey: ['input', name],
        onSuccess: (data : Data) => {
            setEdit(false)
            setVal(data.name)
        }
    });

    const handleSubmit = async (event: FormEvent) => {

        event.preventDefault();

        const formData = new FormData(event.target as HTMLFormElement);

        const data = Object.fromEntries(formData.entries()) as Data;

        mutate(data);
    }

    return (
        <form onSubmit={handleSubmit}>
            {
                !edit &&
                <div className="d-flex align-items-center gap-1">
                    <span>{val}</span>
                    <button className="btn btn-sm btn-transparent text-primary" type="button" onClick={() => setEdit(true)}>
                        <i class="fa-solid fa-pen"></i>
                    </button>
                </div>
            }
            {
                edit &&
                <div className="input-group has-validation" style="width: 250px">
                    <input
                        name={name}
                        type="text"
                        className={clsx('form-control form-control-sm', isError && 'is-invalid')}
                        defaultValue={val}
                        {...props}
                    />
                    <button
                        className="btn btn-outline-success btn-sm"
                        title="Save"
                        type="submit"
                        disabled={isPending}
                    >
                        {isPending && <i className="fa-solid fa-spinner fa-spin"></i>}
                        {!isPending && <i className="fa-solid fa-check"></i>}
                    </button>
                    <button className="btn btn-outline-secondary btn-sm" type="button" title="Cancel" onClick={() => setEdit(false)}>
                        <i className="fa-solid fa-xmark"></i>
                    </button>
                    {
                        isError &&
                        <div className="invalid-feedback">
                            {error.message}
                        </div>
                    }
                </div>
            }
        </form>
    )
}

export function DynamicInput(props: Props) {

    const queryClient = new QueryClient({
        defaultOptions: {
            queries: {
                retry: false,
            }
        }
    });

    return (
        <QueryClientProvider client={queryClient}>
            <Main {...props}/>
        </QueryClientProvider>
    )
}
