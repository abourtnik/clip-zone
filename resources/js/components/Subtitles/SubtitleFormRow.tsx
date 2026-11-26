import {useQueryClient} from "@tanstack/react-query";
import {useState} from "react";
import {SubtitleType, SubtitleLanguages, SubtitleData, SubtitleDataSchema, SubtitleStatus, SubtitleDraft} from "@/types";
import {useRef} from "preact/hooks";
import type {TargetedEvent} from "preact";
import {clsx} from "clsx";
import {useTranslation} from "react-i18next";
import {useErrorMutation} from "@/hooks";
import {addSubtitle, updateSubtitle} from "@/api/clipzone";

type Props = {
    draft: SubtitleDraft | SubtitleType,
    onCancel: () => void;
}

export const SubtitleFormRow = ({draft, onCancel} : Props) => {

    const queryClient = useQueryClient();

    const { t } = useTranslation();

    const [isDrag, setDrag] = useState<boolean>(false);
    const [fileName, setFileName] = useState<string|null>(draft?.file_name ?? null);
    const [error, setError] = useState<string|null>(null);

    const isEdit = 'id' in draft;
    const isAdd = 'tempId' in draft;

    const input = useRef<HTMLInputElement>(null);

    const change = async (event: TargetedEvent<HTMLInputElement>) => {

        const file = event.currentTarget.files?.[0];

        if(!file) {
            return;
        }

        setFileName(file ? file.name : null);
    }

    const {mutate, isPending} = useErrorMutation({
        mutationFn: (data: SubtitleData) => {
            if(isEdit) {
                return updateSubtitle(draft.video_id, draft.id as number, data);
            }

            return addSubtitle(draft.video_id, data);
        },
        onSuccess: async (subtitle: SubtitleType) => {
            await queryClient.invalidateQueries({ queryKey: ['videos', draft.video_id, 'subtitles'] });
            onCancel();
        }
    })

    const handleSubmit = async (e: TargetedEvent<HTMLFormElement>) => {
        setError(null);
        e.preventDefault();

        const validation = SubtitleDataSchema.safeParse(
            Object.fromEntries(new FormData(e.currentTarget))
        );

        if (!validation.success) {
            setError(validation.error.issues[0].message);
            return;
        }

        setError(null);
        mutate(validation.data);
    }

    const formId = `form-${'tempId' in draft ? draft.tempId : draft.id}`;

    return (
        <tr>
            <td class="align-middle">
                <div class="form-check form-switch">
                    <input form={formId} type="hidden" name="is_enabled" value="false" />
                    <input form={formId} class="form-check-input" type="checkbox" name="is_enabled" value="true" defaultChecked={draft?.is_enabled ?? true}/>
                </div>
            </td>
            <td class="align-middle">
                <select
                    form={formId}
                    name="language"
                    class="form-control form-control-sm"
                    required
                    defaultValue={draft?.language ?? ''}
                >
                    <option value="" selected>--- {t('Select Language')} ---</option>
                    {
                        SubtitleLanguages.map((code) => (
                            <option key={code} value={code} className={'text-capitalize'}>
                                {new Intl.DisplayNames([window.LANG], { type: 'language' }).of(code)}
                            </option>
                        ))
                    }
                </select>
            </td>
            <td class="align-middle">
                <input
                    form={formId}
                    type="text"
                    name="name"
                    class={'form-control form-control-sm'}
                    required
                    maxLength={255}
                    minLength={1}
                    placeholder={t('Subtitle name')}
                    defaultValue={draft?.name ?? ''}
                    autoComplete={'off'}
                />
            </td>
            <td class="align-middle">
                <div class={'d-flex gap-1'}>
                    <div class={clsx('tw:border tw:border-dashed p-1 bg-white position-relative', isDrag && 'bg-light-dark')}>
                        <label className="d-none" htmlFor="file"></label>
                        <input
                            form={formId}
                            type="file"
                            name="file"
                            accept=".vtt"
                            className="position-absolute top-0 bottom-0 start-0 end-0 w-100 h-100 outline-none opacity-0 cursor-pointer"
                            ref={input}
                            onChange={change}
                            onDragEnter={() => setDrag(true)}
                            onDragLeave={() => setDrag(false)}
                            onDrop={() => setDrag(false)}
                            required={isAdd}
                        />
                        <span class={'text-primary fw-bold text-sm'}>
                            {fileName ?? t('Upload a WebVTT file (.vtt)')}
                        </span>
                    </div>
                </div>
            </td>
            <td class="align-middle">
                {
                    draft?.status == SubtitleStatus.UPLOADED &&
                    <span class={'badge bg-primary text-white'}>
                         {t('Uploaded')}
                    </span>
                }
            </td>
            <td class="align-middle">
                <form enctype={'multipart/form-data'} class={'d-flex flex-column gap-1'} id={formId} onSubmit={handleSubmit}>
                    <div class={'d-flex align-items-center gap-1'}>
                        <button type={'button'} class="btn bg-transparent text-primary fw-bold btn-sm" onClick={onCancel}>
                            {t('Cancel')}
                        </button>
                        <button
                            type={'submit'}
                            class={clsx(
                                "btn btn-sm d-flex tw:gap-1.5 align-items-center",
                                isEdit && 'btn-primary',
                                isAdd && 'btn-success'
                            )}
                            disabled={isPending}
                        >
                            {isPending && <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>}
                            {!isPending && isAdd && <i class="fa-solid fa-plus"></i>}
                            {isEdit ? t('Update') : t('Add')}
                        </button>
                    </div>
                    {
                        error &&
                        <div class="text-danger text-sm">{error}</div>
                    }
                </form>
            </td>
        </tr>
    )
}
