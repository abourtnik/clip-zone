import {useState, useEffect, useCallback} from 'preact/hooks';
import { memo } from 'preact/compat';

import Interaction from "../Interaction";
import ReplyForm from "./ReplyForm";
import Edit from './Edit'

import ConfirmDelete from './ConfirmDelete'
import {jsonFetch, usePaginateFetch} from "../../hooks";
import {useTranslation} from "react-i18next";
import moment from 'moment';

const Comment = memo(({comment, remove, update, pin}) => {

    const { t } = useTranslation();

    const [onEdit, setOnEdit] = useState(false);
    const [showReply, setShowReply] = useState(false);
    const {items: replies, setItems: setReplies, load, loading, count: repliesCount, setCount, hasMore} =
        usePaginateFetch(`/api/videos/${comment.video_uuid}/comments/${comment.id}/replies`, comment?.replies?.data ?? [], comment?.replies?.meta.total, comment?.replies?.links.next)
    const [showReplies, setShowReplies] = useState(false);

    useEffect(() => {
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    }, [])

    const reply = useCallback(async (data) => {
        return jsonFetch(`/api/videos/${comment.video_uuid}/comments` , {
            method: 'POST',
            body: JSON.stringify({
                ...data,
                video_id: parseInt(comment.video_uuid),
                parent_id: comment.id
            })
        }).then(comment => {
            setReplies(replies => [comment, ...replies]);
            setShowReplies(true);
            setShowReply(false);
            setCount(c => c + 1)
            document.getElementById('message-content-' + comment.id).value = '';
        }).catch(e => e)
    }, []);

    const deleteReply = useCallback (async (reply) => {
        return jsonFetch(`/api/videos/${comment.video_uuid}/comments/${reply.id}` , {
            method: 'DELETE',
        }).then(() => {
            setReplies(replies => replies.filter(r => r.id !== reply.id))
            setCount(c => c - 1)
        }).catch(e => e);
    }, []);

    const updateReply = useCallback (async (reply, content) => {
        return jsonFetch(`/api/videos/${comment.video_uuid}/comments/${reply.id}` , {
            method: 'PUT',
            body: JSON.stringify({
                content: content,
            }),
        }).then(updated_reply => {
            setReplies(replies => replies.map(r => r.id === reply.id ? updated_reply : r))
        }).catch(e => e);
    }, []);

    return (
        <article className="d-flex mb-3 gap-2">
            <a href={comment.user.route} className={'h-100'}>
                <img className="rounded-circle img-fluid" src={comment.user.avatar} alt={comment.user.username + ' avatar'} style="width: 50px;"/>
            </a>
            <div className={'w-100'}>
                <div className={'border p-3 bg-white'}>
                    {
                        comment?.is_pinned &&
                        <div className={'d-flex align-items-center gap-2 mb-3 badge rounded-pill text-bg-primary'} style='width:fit-content'>
                            <i className="fa-solid fa-thumbtack"></i>
                            <span className={'text-white'}>{t('Pinned by')} {comment.video_author.username} </span>
                        </div>
                    }
                    <div className="d-flex justify-content-between align-items-center">
                        <div className={'d-flex flex-wrap align-items-center gap-1'}>
                            <a href={comment.user.route} className={'text-decoration-none text-sm' + (comment.user.is_video_author ? ' badge rounded-pill text-bg-secondary' : '') }>
                                {comment.user.username}
                            </a>
                            <span>•</span>
                            <small className="text-muted">{moment(comment.created_at).fromNow()}</small>
                            {comment.is_updated && <><span>•</span> <small className="text-muted fw-semibold">{t('Modified')}</small></>}
                        </div>
                        {
                            <div className={'dropdown'}>
                                <button className={'bg-transparent btn-sm dropdown-toggle without-caret'} type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                    <i className="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul className="dropdown-menu">
                                    {
                                        comment?.can_pin &&
                                        <li>
                                            <button onClick={() => pin(comment, comment.is_pinned ? 'unpin' : 'pin')} className="dropdown-item d-flex align-items-center gap-3" >
                                                <i className="fa-solid fa-thumbtack"></i>
                                                {comment.is_pinned ? t('Unpin') : t('Pin')}
                                            </button>
                                        </li>
                                    }
                                    {
                                        comment?.can_update &&
                                        <li>
                                            <button className="dropdown-item d-flex align-items-center gap-3" onClick={() => setOnEdit(onEdit => !onEdit)}>
                                                <i className="fa-solid fa-pen"></i>
                                                {t('Edit')}
                                            </button>
                                        </li>
                                    }
                                    {
                                        comment?.can_delete && <ConfirmDelete comment={comment} onDelete={remove}/>
                                    }
                                    <li>
                                        {
                                            window.USER ?
                                                comment.can_report ?
                                                    <button
                                                        className="dropdown-item d-flex align-items-center gap-3"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#report"
                                                        data-id={comment.id}
                                                        data-type={comment.class}
                                                    >
                                                        <i className="fa-solid fa-flag"></i>
                                                        {t('Report')}
                                                    </button>
                                                    :
                                                    comment.reported_at &&
                                                    <div className="dropdown-item d-flex align-items-center gap-2 mb-0 text-sm py-2">
                                                        <i className="fa-solid fa-flag"></i>
                                                        <span>{t('Reported')} {comment.reported_at}</span>
                                                    </div>
                                                :
                                                <button
                                                    className="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="popover"
                                                    data-bs-placement="left"
                                                    data-bs-title={t('Need to report the comment?')}
                                                    data-bs-trigger="focus"
                                                    data-bs-html="true"
                                                    data-bs-content={`${t('Sign in to report inappropriate content.')}<hr><a href='/login' class='btn btn-primary btn-sm'>${t('Sign in')}`}
                                                >
                                                    <i className="fa-solid fa-flag"></i>
                                                    {t('Report')}
                                                </button>
                                        }
                                    </li>
                                </ul>
                            </div>
                        }
                    </div>
                    <Edit comment={comment} update={update} setOnEdit={setOnEdit} onEdit={onEdit}/>
                    <div className="d-flex align-items-center gap-2 mt-1">
                        <div className="d-flex align-items-center gap-2">
                            {
                                window.USER ?
                                    <Interaction
                                        active={JSON.stringify({
                                            'like': comment.liked_by_auth_user,
                                            'dislike': comment.disliked_by_auth_user
                                        })}
                                        model={comment.class}
                                        target={comment.id}
                                        count={JSON.stringify({
                                            'likes_count': comment.likes_count,
                                            'dislikes_count': comment.dislikes_count
                                        })}
                                    />
                                    :
                                    <div className="d-flex justify-content-between bg-light-dark rounded-4">
                                        <button
                                            className="hover-grey btn btn-sm border border-0 text-black px-3 rounded-5 rounded-end d-fl"
                                            data-bs-toggle="popover"
                                            data-bs-placement="left"
                                            data-bs-title={t('Like this comment ?')}
                                            data-bs-trigger="focus"
                                            data-bs-html="true"
                                            data-bs-content={`${t('Sign in to make your opinion count.')}<hr><a href='/login' class='btn btn-primary btn-sm'>${t('Sign in')}</a>`}
                                        >
                                            <div className={'d-flex gap-2 align-items-center'}>
                                                <i className="fa-regular fa-thumbs-up"></i>
                                                {comment.likes_count > 0 && <span>{comment.likes_count}</span>}
                                            </div>
                                        </button>
                                        <div className="vr"></div>
                                        <button
                                            className="hover-grey btn btn-sm border border-0 text-black px-3 rounded-5 rounded-start"
                                            data-bs-toggle="popover"
                                            data-bs-placement="right"
                                            data-bs-title={t('Don\'t like this comment ?')}
                                            data-bs-trigger="focus"
                                            data-bs-html="true"
                                            data-bs-content={`${t('Sign in to make your opinion count.')}<hr><a href='/login' class='btn btn-primary btn-sm'>${t('Sign in')}</a>`}
                                        >
                                            <div className={'d-flex gap-2 align-items-center'}>
                                                <i className="fa-regular fa-thumbs-down"></i>
                                                {comment.dislikes_count > 0 && <span>{comment.dislikes_count}</span>}
                                            </div>
                                        </button>
                                    </div>
                            }
                        </div>
                        {
                            !comment.is_reply ?
                                window.USER ?
                                    <button
                                        className="btn btn-sm text-info"
                                        onClick={() => setShowReply(true)}
                                    >
                                        {t('Reply')}
                                    </button> :
                                    <button
                                        className="btn btn-sm text-info"
                                        data-bs-toggle="popover"
                                        data-bs-placement="right"
                                        data-bs-title={t('Want to reply to this comment ?')}
                                        data-bs-trigger="focus"
                                        data-bs-html="true"
                                        data-bs-content={`${t('Sign in for reply.')}<hr><a href='/login' class='btn btn-primary btn-sm'>${t('Sign in')}</a>`}
                                    >
                                        {t('Reply')}
                                    </button> : null
                        }
                        {
                            comment?.is_video_author_like &&
                            <div className={'position-relative'} data-bs-toggle="tooltip" data-bs-title={'Liked by ' + comment.video_author.username}>
                                <img className="rounded-circle img-fluid" src={comment.video_author.avatar} alt={comment.video_author.username + ' avatar'} style="width: 30px;"/>
                                <i
                                    style="width: 10px;height: 10px;"
                                    className={'fa-2xs fa-solid fa-thumbs-up text-white position-absolute bottom-0 left-60 dot rounded-circle d-flex justify-content-center align-items-center bg-success'}></i>
                            </div>
                        }
                    </div>
                    {showReply && <ReplyForm setShowReply={setShowReply} comment={comment} reply={reply}/>}
                    {
                    comment?.has_replies &&
                        <button className={'btn btn-sm text-primary my-1 mt-2 ps-0 fw-bold d-flex align-items-center gap-2'} onClick={() => setShowReplies(showReplies => !showReplies)}>
                            <i className={'fa-solid fa-' + (showReplies ? 'chevron-up' : 'chevron-down')}></i>
                            {
                                comment?.is_video_author_reply &&
                                <>
                                    <img className="rounded-circle img-fluid" src={comment.video_author.avatar} alt={comment.video_author.username + ' avatar'} style="width: 30px;"/>
                                    <span>•</span>
                                </>
                            }
                            {t( (showReplies ? 'Hide ' : 'Show ') + 'replies', { count: repliesCount })}
                        </button>
                    }
                </div>
                {
                    showReplies &&
                    <div className={'mt-3'}>
                        {
                            replies.map(comment =>
                                <Comment
                                    key={comment.id}
                                    comment={comment}
                                    remove={deleteReply}
                                    update={updateReply}
                                />
                            )
                        }
                        {
                            hasMore &&
                            <div className={'w-100 d-flex justify-content-end'}>
                                <button className={'btn btn-sm text-primary fw-bold d-flex align-items-center gap-2'} onClick={() => load()}>
                                    {
                                        loading ?
                                            <div className={'d-flex gap-2 align-items-center'}>
                                                <div className="spinner-border spinner-border-sm" role="status">
                                                    <span className="visually-hidden">{t('Loading ...')}</span>
                                                </div>
                                                <span>{t('Loading ...')}</span>
                                            </div> :
                                            <>
                                                <i className="fa-regular fa-plus"></i>
                                                <span>{t('Show more responses')}</span>
                                            </>
                                    }
                                </button>
                            </div>
                        }
                    </div>
                }
            </div>
        </article>
    )
});

export default Comment;
