import {useState} from 'preact/hooks';
import { memo } from 'preact/compat';
import {useTranslation} from "react-i18next";
import {Interaction} from "../Interactions";
import {CommentType} from "@/types";
import {Popover, Tooltip, OverlayTrigger} from 'react-bootstrap';
import {UpdateComment, DeleteComment, PinComment, ReportComment} from "@/components/Comments/Actions";
import {ReplyForm} from "@/components/Comments/ReplyForm";
import {Replies} from "@/components/Comments/Replies";
import moment from 'moment';

type Props = {
    comment: CommentType,
}

const Comment = memo(({comment} : Props) => {

    const { t } = useTranslation();

    const [edit, setEdit] = useState<boolean>(false);

    const [showReplyForm, setShowReplyForm] = useState<boolean>(false);

    const [showReplies, setShowReplies] = useState<boolean>(false);

    const reply = () => {
        setShowReplyForm(false);
        setShowReplies(true);
    }

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
                            <span className={'text-white'}>{t('Pinned by')} {comment.video_author?.username} </span>
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
                                <button className={'bg-transparent btn-sm dropdown-toggle without-caret'} type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                                    <i className="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul className="dropdown-menu">
                                    {comment?.can_pin && <PinComment comment={comment}/>}
                                    {
                                        comment?.can_update &&
                                        <li>
                                            <button className="dropdown-item d-flex align-items-center gap-3" onClick={() => setEdit(true)}>
                                                <i className="fa-solid fa-pen"></i>
                                                {t('Edit')}
                                            </button>
                                        </li>
                                    }
                                    {
                                        comment?.can_delete && <DeleteComment comment={comment}/>
                                    }
                                    <ReportComment comment={comment}/>
                                </ul>
                            </div>
                        }
                    </div>
                    <UpdateComment comment={comment} edit={edit} setEdit={setEdit} />
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
                                        <OverlayTrigger
                                            trigger="click"
                                            placement={'top'}
                                            rootClose={true}
                                            overlay={
                                                <Popover style={{position: 'fixed'}}>
                                                    <Popover.Header as="h3">{t('Like this comment ?')}</Popover.Header>
                                                    <Popover.Body>
                                                        {t('Sign in to make your opinion count.')}
                                                        <hr/>
                                                        <a href='/login' className='btn btn-primary btn-sm'>{t('Sign in')}</a>
                                                    </Popover.Body>
                                                </Popover>
                                            }
                                        >
                                            <button className="hover-grey btn btn-sm border border-0 text-black px-3 rounded-5 rounded-end d-fl">
                                                <div className={'d-flex gap-2 align-items-center'}>
                                                    <i className="fa-regular fa-thumbs-up"></i>
                                                    {comment.likes_count > 0 && <span>{comment.likes_count}</span>}
                                                </div>
                                            </button>
                                        </OverlayTrigger>
                                        <div className="vr"></div>
                                        <OverlayTrigger
                                            trigger="click"
                                            placement={'top'}
                                            rootClose={true}
                                            overlay={
                                                <Popover style={{position: 'fixed'}}>
                                                    <Popover.Header as="h3">{t('Don\'t like this comment ?')}</Popover.Header>
                                                    <Popover.Body>
                                                        {t('Sign in to make your opinion count.')}
                                                        <hr/>
                                                        <a href='/login' className='btn btn-primary btn-sm'>{t('Sign in')}</a>
                                                    </Popover.Body>
                                                </Popover>
                                            }
                                        >
                                            <button className="hover-grey btn btn-sm border border-0 text-black px-3 rounded-5 rounded-start">
                                                <div className={'d-flex gap-2 align-items-center'}>
                                                    <i className="fa-regular fa-thumbs-down"></i>
                                                    {comment.dislikes_count > 0 && <span>{comment.dislikes_count}</span>}
                                                </div>
                                            </button>
                                        </OverlayTrigger>
                                    </div>
                            }
                        </div>
                        {
                            !comment.is_reply && (
                                window.USER ?
                                    <button
                                        className="btn btn-sm text-info"
                                        onClick={() => setShowReplyForm(v => !v)}
                                    >
                                        {t('Reply')}
                                    </button> :
                                    <OverlayTrigger
                                        trigger="click"
                                        placement={'top'}
                                        rootClose={true}
                                        overlay={
                                            <Popover style={{position: 'fixed'}}>
                                                <Popover.Header as="h3">{t('Want to reply to this comment ?')}</Popover.Header>
                                                <Popover.Body>
                                                    {t('Sign in for reply.')}
                                                    <hr/>
                                                    <a href='/login' className='btn btn-primary btn-sm'>{t('Sign in')}</a>
                                                </Popover.Body>
                                            </Popover>
                                        }
                                    >
                                        <button className="btn btn-sm text-info">{t('Reply')}</button>
                                    </OverlayTrigger>
                            )
                        }
                        {
                            comment?.is_video_author_like &&
                            <OverlayTrigger
                                placement="top"
                                overlay={
                                    <Tooltip id="button-tooltip">
                                        Liked by {comment.video_author?.username}
                                    </Tooltip>
                                }
                            >
                                <div className={'position-relative'}>
                                    <img className="rounded-circle img-fluid" src={comment.video_author?.avatar} alt={comment.video_author?.username + ' avatar'} style="width: 30px;"/>
                                    <i style="width: 10px;height: 10px;" className={'fa-2xs fa-solid fa-thumbs-up text-white position-absolute bottom-0 left-60 dot rounded-circle d-flex justify-content-center align-items-center bg-success'}></i>
                                </div>
                            </OverlayTrigger>
                        }
                    </div>
                    {showReplyForm && <ReplyForm comment={comment} reply={reply} setShowReplyForm={setShowReplyForm}/>}
                    {
                        comment?.has_replies &&
                        <button
                            className={'btn btn-sm text-primary mt-3 ps-0 fw-bold d-flex align-items-center gap-2'}
                            onClick={() => setShowReplies(v => !v)}>
                            <i className={'fa-solid fa-' + (showReplies ? 'chevron-up' : 'chevron-down')}></i>
                            {
                                comment?.is_video_author_reply &&
                                <>
                                    <img className="rounded-circle img-fluid" src={comment.video_author?.avatar} alt={comment.video_author?.username + ' avatar'} style="width: 30px;"/>
                                    <span>•</span>
                                </>
                            }
                            {t( (showReplies ? 'Hide ' : 'Show ') + 'replies', { count: comment.replies_count})}
                        </button>
                    }
                </div>
                <Replies comment={comment} key={comment.id} showReplies={showReplies}/>
            </div>
        </article>
    )
});

export default Comment;
