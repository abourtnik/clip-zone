import register from 'preact-custom-element';

import { VideoUpload, VideosList, UserVideos, Player } from './Videos';
import { InteractionList, Interaction } from './Interactions';
import { SearchBar, SearchModel } from './Search';
import {NotificationsList} from "./Notifications/NotificationsList";
import {UserPlaylists, Save} from "./Playlists";

import {CommentsList} from "./Comments/CommentsList";
import {UserReplies} from "./Comments/UserReplies";

import ImageUpload from "./Images/ImageUpload";
import ImageLoaded from "./Images/ImageLoaded";
import Thumbnails from "./Images/Thumbnails";

import {Subscribe, Report} from "./Actions";
import {VideoAutoplaySwitcher, ThemeSwitcher, AutoplayNextVideoSwitcher} from "./Switchs";

register(Subscribe, 'subscribe-button');
register(Interaction, 'interaction-button', [], { shadow: false });
register(CommentsList, 'comments-area', [], { shadow: false });
register(VideosList, 'videos-area', [], { shadow: false });
register(InteractionList, 'interactions-area', [], { shadow: false });
register(SearchBar, 'search-bar', [], { shadow: false });
register(UserReplies, 'user-replies', [], { shadow: false });
register(UserVideos, 'user-videos', [], { shadow: false });
register(UserPlaylists, 'playlist-videos', [], { shadow: false });
register(NotificationsList, 'site-notifications', [], { shadow: false });
register(ImageUpload, 'image-upload', ['source'], { shadow: false });
register(VideoUpload, 'video-upload', [], { shadow: false });
register(ImageLoaded, 'image-loaded', ['hover'], { shadow: false });
register(Save, 'save-video', [], { shadow: false });
register(SearchModel, 'search-model', [], { shadow: false });
register(Thumbnails, 'thumbnails-select', [], { shadow: false });
register(Report, 'report-button', [], { shadow: false });
register(VideoAutoplaySwitcher, 'video-autoplay-switch', [], { shadow: false });
register(AutoplayNextVideoSwitcher, 'autoplay-switch', [], { shadow: false });
register(ThemeSwitcher, 'theme-switch', [], { shadow: false });
register(Player, 'video-player', [], { shadow: false });


