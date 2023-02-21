<?php

use App\Models\Video;
use App\Models\Playlist;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// VIDEOS

Breadcrumbs::for('videos', function (BreadcrumbTrail $trail) {
    $trail->push('Videos', route('user.videos.index'));
});

Breadcrumbs::for('create_video', function (BreadcrumbTrail $trail, Video $video) {
    $trail->parent('videos');
    $trail->push('Create new video', route('user.videos.create', $video));
});

Breadcrumbs::for('edit_video', function (BreadcrumbTrail $trail, Video $video) {
    $trail->parent('videos');
    $trail->push($video->title, route('user.videos.edit', $video));
});

Breadcrumbs::for('show_video', function (BreadcrumbTrail $trail, Video $video) {
    $trail->parent('videos');
    $trail->push($video->title, route('user.videos.edit', $video));
});

// PLAYLISTS
Breadcrumbs::for('playlists', function (BreadcrumbTrail $trail) {
    $trail->push('Playlists', route('user.playlists.index'));
});

// Playlists > Create Playlist
Breadcrumbs::for('create_playlist', function (BreadcrumbTrail $trail) {
    $trail->parent('playlists');
    $trail->push('Create new playlist', route('user.playlists.create'));
});

// Playlists > Update Playlist [Playlist]
Breadcrumbs::for('edit_playlist', function (BreadcrumbTrail $trail, Playlist $playlist) {
    $trail->parent('playlists');
    $trail->push($playlist->title, route('user.playlists.edit', $playlist));
});
