<?php

use App\Models\Video;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Videos
Breadcrumbs::for('videos', function (BreadcrumbTrail $trail) {
    $trail->push('Videos', route('user.videos.index'));
});

// Videos > Create Video
Breadcrumbs::for('create_video', function (BreadcrumbTrail $trail) {
    $trail->parent('videos');
    $trail->push('Create new video', route('user.videos.create'));
});

// Videos > Update Video [Video]
Breadcrumbs::for('edit_video', function (BreadcrumbTrail $trail, Video $video) {
    $trail->parent('videos');
    $trail->push($video->title, route('user.videos.edit', $video));
});
