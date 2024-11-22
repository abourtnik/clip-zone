import {useState, useCallback, useEffect} from 'preact/hooks';
import {memo} from 'preact/compat';
import Form from "./Save/Form";
import {jsonFetch} from '../oldhooks'
import { useMachine } from '@xstate/react';
import {createMachine} from "xstate";

const saveMachine = createMachine({
    predictableActionArguments: true,
    id: 'save',
    initial: 'disabled',
    states: {
        disabled : {
            on: {
                SAVE: { target: 'save' }
            }
        },
        save: {
            on: {
                LOADING: { target: 'loading' },
            }
        },
        loading: {
            on: {
                SAVED: { target: 'saved' },
                ERROR: { target: 'save' }
            }
        },
        saved: {
            on: {
                SAVE: { target: 'save' }
            }
        }
    }
})

const Save = memo(({video}) => {

    const [playlists, setPlaylists] = useState([]);
    const [loading, setLoading] = useState(true);

    const [saveState, send] = useMachine(saveMachine);

    useEffect( async () => {
        return jsonFetch(`/api/users/${window.USER.id}/playlists?video_id=${video}` , {
        }).then(response => {
            setPlaylists(response.data);
            setLoading(false)
            send('SAVE')
        }).catch(e => e)
    }, []);

    const createPlaylist = useCallback(async (data) => {
        return jsonFetch(`/profile/playlists` , {
            method: 'POST',
            body: JSON.stringify(data)
        }).then(playlist => {
            setPlaylists(playlists => [...playlists, playlist]);
        }).catch(e => e)
    }, []);

    const save = useCallback(async () => {
        send('LOADING')
        return jsonFetch(`/api/playlists/save` , {
            method: 'POST',
            body: JSON.stringify({
                video_id : video,
                playlists : Array.from(document.querySelectorAll('input[name="playlists[]"]'), input => ({
                    'id': input.dataset.id,
                    'checked': input.checked
                })),
            })
        }).then(() => {
            send('SAVED')
            setTimeout(() => {
                send('SAVE')
            }, 3000)
        }).catch(e => send('ERROR'))
    }, []);

    const handleChange = (event) => {
        const checkbox = event.target;
        const id = checkbox.dataset.id;
        setPlaylists(playlists => playlists.map(p => p.id == id ? {...p, has_video: checkbox.checked} : p))
    }

    return (
        <>
        <div className="modal-body">
            {
                !loading ?
                    <>
                        {
                            playlists.map(playlist => (
                                <div className="form-check mb-3">
                                    <div className="d-flex align-items-center gap-3">
                                        <div>
                                            <input
                                                className="form-check-input"
                                                name="playlists[]"
                                                type="checkbox"
                                                value={playlist.id}
                                                id={'playlist-' + playlist.id}
                                                data-id={playlist.id}
                                                checked={playlist.has_video}
                                                onChange={handleChange}
                                            />
                                            <label className="form-check-label" htmlFor={'playlist-' + playlist.id}>
                                                {playlist.title}
                                            </label>
                                        </div>
                                        <i className={'fa-solid fa-' + playlist.icon}></i>&nbsp;
                                    </div>
                                </div>
                            ))
                        }
                        <Form create={createPlaylist}/>
                    </>
                :
                    <div className={'alert alert-primary d-flex align-items-center gap-3 mb-0'}>
                        <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Loading your playlists ...</span>
                    </div>
            }
        </div>
        <div className="modal-footer">
            <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button onClick={save} disabled={!saveState.matches('save') || saveState.matches('disabled') } type="button" className={'btn ' + (saveState.matches('saved') ? 'btn-success' : 'btn-primary')}>
                {
                    (saveState.matches('save') || saveState.matches('disabled')) &&
                    <span>Save</span>
                }
                {
                    saveState.matches('loading') &&
                    <div className={'d-flex align-items-center gap-2'}>
                        <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Save</span>
                    </div>
                }
                {
                    saveState.matches('saved') &&
                    <div className={'d-flex align-items-center gap-2'}>
                        <i className="fa-solid fa-check"></i>
                        <span>Saved</span>
                    </div>
                }
            </button>
        </div>
    </>
    )
});

export default Save;
