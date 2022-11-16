import register from 'preact-custom-element';

import Subscribe from "./Subscribe";
import Like from "./Like";
import Comments from "./Comments";
import Description from "./Description";

register(Subscribe, 'subscribe-button');
register(Like, 'like-button', [], { shadow: false });
register(Comments, 'comments-area', [], { shadow: false });
register(Description, 'description-box', [], { shadow: false });


