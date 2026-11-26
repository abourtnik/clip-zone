import {memo} from "preact/compat";
import {useTranslation} from "react-i18next";
import moment from 'moment';
import {SubtitleType, SubtitleStatus} from "@/types";
import {DeleteSubtitle} from "@/components/Subtitles/DeleteSubtitle";
import {useState} from "react";
import {SubtitleFormRow} from "@/components/Subtitles/SubtitleFormRow";
import {useErrorMutation} from "@/hooks";
import {useQueryClient} from "@tanstack/react-query";
import {toggleSubtitle} from "@/api/clipzone";

export const Subtitle = memo(({subtitle} : {subtitle: SubtitleType}) => {

    const { t } = useTranslation();

    const queryClient = useQueryClient();

    const [isEditing, setIsEditing] = useState(false);

    if (isEditing) {
        return (
            <SubtitleFormRow draft={subtitle} onCancel={() => setIsEditing(false)}/>
        )
    }

    const {mutate: toggle, isPending: isPendingToggle} = useErrorMutation({
        mutationFn: (subtitle: SubtitleType) => toggleSubtitle(subtitle.video_id, subtitle.id),
        onSuccess: () => queryClient.invalidateQueries({ queryKey: ['videos', subtitle.video_id, 'subtitles'] })
    })

    return (
        <tr>
            <td>
                <div class="form-check form-switch">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        value="1"
                        name="is_enabled"
                        checked={subtitle.is_enabled}
                        onChange={() => toggle(subtitle)}
                        disabled={isPendingToggle || subtitle.status == SubtitleStatus.PENDING || subtitle.status == SubtitleStatus.ERROR}
                    />
                </div>
            </td>
            <td>
                {
                    subtitle.language &&
                    <span class={'badge bg-secondary text-white text-capitalize'}>
                        {new Intl.DisplayNames([window.LANG], { type: 'language' }).of(subtitle.language)}
                    </span>
                }
                {
                    (!subtitle.language && subtitle.status === SubtitleStatus.PENDING) &&
                    <span class={'tw:border tw:border-dashed text-sm p-1 text-primary fw-bold'}>
                        {t('Detecting ...')}
                    </span>
                }
                {
                    (!subtitle.language && (subtitle.status === SubtitleStatus.GENERATED || subtitle.status === SubtitleStatus.ERROR)) &&
                    <span class={'badge bg-secondary text-white text-capitalize'}>
                        {t('Unknown')}
                    </span>
                }
            </td>
            <td>
                <span class={'fw-bold'}>{subtitle.name}</span>
                <div class={'d-flex gap-2 gap-xxl-1 text-secondary mt-2 align-items-center flex-wrap'}>
                    <span class={'text-sm'}>
                        {subtitle.is_generated && t('Generated')}
                        {subtitle.is_uploaded && t('Imported')}
                    </span>
                    <span class={'text-sm'}>{moment(subtitle.created_at).fromNow()}</span>
                    {
                        subtitle.is_empty &&
                        <div class={'d-flex align-items-center gap-1'}>
                            <span class={'text-sm d-none d-xxl-block'}>•</span>
                            <span class={'badge bg-warning'}>{t('Empty Subtitles')}</span>
                        </div>
                    }
                </div>
            </td>
            <td>
                {
                    subtitle.file_url &&
                    <a
                        download={subtitle.name} href={subtitle.file_url}
                        class="badge bg-secondary text-white d-inline-flex align-items-center gap-2 p-2 text-decoration-none"
                        title={t('Download subtitle')}
                    >
                        <i class="fa-solid fa-download"></i>
                        <span class={'text-sm'}>{t('File')}</span>
                    </a>
                }
            </td>
            <td>
                {
                    subtitle.status == SubtitleStatus.PENDING &&
                    <span class={'badge bg-primary text-white'}>
                         {t('Generating ...')}
                     </span>
                }
                {
                    subtitle.status == SubtitleStatus.GENERATED &&
                    <span class={'badge bg-primary text-white'}>
                         {t('Generated')}
                    </span>
                }
                {
                    subtitle.status == SubtitleStatus.UPLOADED &&
                    <span class={'badge bg-primary text-white'}>
                         {t('Uploaded')}
                    </span>
                }
                {
                    subtitle.status == SubtitleStatus.ERROR &&
                    <span class={'badge bg-danger text-white'}>
                         {t('Error')}
                    </span>
                }
            </td>
            <td>
                {
                    subtitle.is_uploaded &&
                    <div class={'d-flex align-items-center gap-1'}>
                        <DeleteSubtitle subtitle={subtitle}/>
                        <button type={'button'} class="btn btn-primary btn-sm" title="Edit subtitle" onClick={() => setIsEditing(true)}>
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                    </div>
                }
            </td>
        </tr>
    )
});
