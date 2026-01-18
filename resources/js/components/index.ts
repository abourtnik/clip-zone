import register from 'preact-custom-element';
import r2wc from "@r2wc/react-to-web-component"

import { VideoUpload, VideosList, UserVideos, Player} from './Videos';
import { InteractionList, Interaction } from './Interactions';
import { SearchBar, SearchModel } from './Search';
import {NotificationsList} from "./Notifications/NotificationsList";
import {HistoryList} from "./History/HistoryList";
import {Save, Playlist} from "./Playlists";

import {CommentsList} from "./Comments/CommentsList";
import {UserReplies} from "./Comments/UserReplies";

import ImageUpload from "./Images/ImageUpload";
import ImageLoaded from "./Images/ImageLoaded";

import {Thumbnails} from "./Videos/Thumbnails";

import {Subscribe, Report} from "./Actions";
import {VideoAutoplaySwitcher, ThemeSwitcher, AutoplayNextVideoSwitcher} from "./Switchs";
import {DynamicInput} from "./Commons";

register(Subscribe, 'subscribe-button');
register(CommentsList, 'comments-area', [], { shadow: false });
register(VideosList, 'videos-area', [], { shadow: false });
register(InteractionList, 'interactions-area', [], { shadow: false });
register(SearchBar, 'search-bar', [], { shadow: false });
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
register(Report, 'report-button', [], { shadow: false });
register(VideoAutoplaySwitcher, 'video-autoplay-switch', [], { shadow: false });
register(AutoplayNextVideoSwitcher, 'autoplay-switch', [], { shadow: false });
register(ThemeSwitcher, 'theme-switch', [], { shadow: false });
register(DynamicInput, 'dynamic-input', [], { shadow: false });
register(HistoryList, 'user-history', [], { shadow: false });

customElements.define("interaction-buttons", r2wc(Interaction, {
    props: {
        model: "string",
        target: "number",
        likes: "number",
        dislikes: "number",
        liked: "boolean",
        disliked: "boolean",
        showCount: "boolean",
    },
}));

customElements.define("video-player", r2wc(Player, {
    props: {
        video_id: "string",
        thumbnail_url: "string",
        file_url: "string",
        next_video: "string",
        subtitles: "json",
        show_ad: "boolean",
    },
}));


