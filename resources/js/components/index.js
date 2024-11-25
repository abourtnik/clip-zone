import register from 'preact-custom-element';

import { VideoUpload, VideosList, UserVideos } from './Videos';
import { InteractionList, Interaction } from './Interactions';
import { SearchBar, SearchModel } from './Search';
import {NotificationsList} from "./Notifications/NotificationsList";

import Subscribe from "./Subscribe";
import {CommentsList} from "./Comments/CommentsList";
import {UserReplies} from "./Comments/UserReplies";
import LineChart from "./LineChart";
import Playlist from "./Playlist";

import ImageUpload from "./ImageUpload";
import ImageLoaded from "./ImageLoaded";
import Save from "./Save";
import Thumbnails from "./Thumbnails";

register(Subscribe, 'subscribe-button');
register(Interaction, 'interaction-button', [], { shadow: false });
register(CommentsList, 'comments-area', [], { shadow: false });
register(VideosList, 'videos-area', [], { shadow: false });
register(InteractionList, 'interactions-area', [], { shadow: false });
register(SearchBar, 'search-bar', [], { shadow: false });
register(LineChart, 'line-chart', [], { shadow: false });
register(UserReplies, 'user-replies', [], { shadow: false });
register(UserVideos, 'user-videos', [], { shadow: false });
register(Playlist, 'playlist-videos', [], { shadow: false });
register(NotificationsList, 'site-notifications', [], { shadow: false });
register(ImageUpload, 'image-upload', ['source'], { shadow: false });
register(VideoUpload, 'video-upload', [], { shadow: false });
register(ImageLoaded, 'image-loaded', ['hover'], { shadow: false });
register(Save, 'save-video', [], { shadow: false });
register(SearchModel, 'search-model', [], { shadow: false });
register(Thumbnails, 'thumbnails-select', [], { shadow: false });


