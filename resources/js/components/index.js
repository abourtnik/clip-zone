import register from 'preact-custom-element';

import Subscribe from "./Subscribe";
import Like from "./Like";
import Comments from "./Comments";
import Description from "./Description";
import Interactions from "./Interactions";
import Search from "./Search";

register(Subscribe, 'subscribe-button');
register(Like, 'likes-button', [], { shadow: false });
register(Comments, 'comments-area', [], { shadow: false });
register(Description, 'description-box', [], { shadow: false });
register(Interactions, 'interactions-area', [], { shadow: false });
register(Search, 'search-bar', [], { shadow: false });


