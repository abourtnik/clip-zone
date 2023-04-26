export default {
    accepted_format : ['image/jpeg', 'image/png', 'image/webp', 'image/jpeg'],
    avatar: {
        maxSize: 4,
        rounded: true,
        minWidth: 98,
        minHeight: 98,
        width: 176,
        height: 176,
        cropArea : 1.0,
        aspectRatio : 1,
        resizeable : true
    },
    banner: {
        maxSize: 6,
        rounded: false,
        minWidth: 1060,
        minHeight: 175,
        width: 1060,
        height: 175,
        cropArea : 0.1,
        aspectRatio : null,
        resizeable : false
    },
    thumbnail: {
        maxSize: 2,
        rounded: false,
        minWidth: 640,
        minHeight: 360,
        width: 720,
        height: 404,
        cropArea : 1.0,
        aspectRatio : 16/9,
        resizeable : true
    },
    video:  {
        maxSize: 1000,
        accepted_format : ['video/mp4', 'video/webm', 'audio/ogg'],
    }
}
