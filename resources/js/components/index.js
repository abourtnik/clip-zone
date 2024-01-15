import register from 'preact-custom-element';

import Subscribe from "./Subscribe";
import Interaction from "./Interaction";
import Comments from "./Comments";
import Videos from "./Videos";
import Interactions from "./Interactions";
import Search from "./Search";
import LineChart from "./LineChart";
import Replies from "./Replies";
import UserVideos from "./UserVideos";
import Playlist from "./Playlist";
import Notifications from "./Notifications";
import ImageUpload from "./ImageUpload";
import VideoUpload from "./VideoUpload";
import ImageLoaded from "./ImageLoaded";
import Save from "./Save";
import SearchModel from "./SearchModel";

register(Subscribe, 'subscribe-button');
register(Interaction, 'interaction-button', [], { shadow: false });
register(Comments, 'comments-area', [], { shadow: false });
register(Videos, 'videos-area', [], { shadow: false });
register(Interactions, 'interactions-area', [], { shadow: false });
register(Search, 'search-bar', [], { shadow: false });
register(LineChart, 'line-chart', [], { shadow: false });
register(Replies, 'replies-area', [], { shadow: false });
register(UserVideos, 'user-videos', [], { shadow: false });
register(Playlist, 'playlist-videos', [], { shadow: false });
register(Notifications, 'site-notifications', [], { shadow: false });
register(ImageUpload, 'image-upload', ['source'], { shadow: false });
register(VideoUpload, 'video-upload', [], { shadow: false });
register(ImageLoaded, 'image-loaded', ['hover'], { shadow: false });
register(Save, 'save-video', [], { shadow: false });
register(SearchModel, 'search-model', [], { shadow: false });


