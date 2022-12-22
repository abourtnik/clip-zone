import register from 'preact-custom-element';

import Subscribe from "./Subscribe";
import Like from "./Like";
import Comments from "./Comments";
import Videos from "./Videos";
import Interactions from "./Interactions";
import Search from "./Search";
import LineChart from "./LineChart";

register(Subscribe, 'subscribe-button');
register(Like, 'likes-button', [], { shadow: false });
register(Comments, 'comments-area', [], { shadow: false });
register(Videos, 'videos-area', [], { shadow: false });
register(Interactions, 'interactions-area', [], { shadow: false });
register(Search, 'search-bar', [], { shadow: false });
register(LineChart, 'line-chart', [], { shadow: false });


