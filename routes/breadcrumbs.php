<?php

use App\Models\Video;
use App\Models\Playlist;
use App\Models\Category;
use App\Models\Subtitle;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// VIDEOS

Breadcrumbs::for('videos', function (BreadcrumbTrail $trail) {
    $trail->push(__('Videos'), route('user.videos.index'));
});

Breadcrumbs::for('create_video', function (BreadcrumbTrail $trail, Video $video) {
    $trail->parent('videos');
    $trail->push(__('Create new video'), route('user.videos.create', $video));
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

// Index
Breadcrumbs::for('playlists', function (BreadcrumbTrail $trail) {
    $trail->push(__('Playlists'), route('user.playlists.index'));
});

// Playlists > Create Playlist
Breadcrumbs::for('create_playlist', function (BreadcrumbTrail $trail) {
    $trail->parent('playlists');
    $trail->push(__('Create new playlist'), route('user.playlists.create'));
});

// Playlists > Update Playlist [Playlist]
Breadcrumbs::for('edit_playlist', function (BreadcrumbTrail $trail, Playlist $playlist) {
    $trail->parent('playlists');
    $trail->push($playlist->title, route('user.playlists.edit', $playlist));
});

// SUBTITLES

// Index
Breadcrumbs::for('subtitles', function (BreadcrumbTrail $trail) {
    $trail->push(__('Subtitles'), route('user.subtitles.list'));
});

// Video Subtitles
Breadcrumbs::for('videos_subtitles', function (BreadcrumbTrail $trail, Video $video) {
    $trail->parent('subtitles');
    $trail->push($video->title, route('user.videos.subtitles.index', $video));
});

// Video Subtitles > Create Subtitle
Breadcrumbs::for('create_subtitle', function (BreadcrumbTrail $trail, Video $video) {
    $trail->parent('videos_subtitles', $video);
    $trail->push(__('Create new subtitle'), route('user.videos.subtitles.create', $video));
});

// Video Subtitles > Update Subtitle [Subtitle]
Breadcrumbs::for('edit_subtitle', function (BreadcrumbTrail $trail, Subtitle $subtitle) {
    $trail->parent('videos_subtitles', $subtitle->video);
    $trail->push($subtitle->name, route('user.subtitles.update', $subtitle));
});

// CATEGORIES

Breadcrumbs::for('categories', function (BreadcrumbTrail $trail) {
    $trail->push(__('Categories'), route('admin.categories.index'));
});

// Categories > Create Category
Breadcrumbs::for('create_category', function (BreadcrumbTrail $trail) {
    $trail->parent('categories');
    $trail->push(__('Create new category'), route('admin.categories.create'));
});

// Categories > Update Category [Category]
Breadcrumbs::for('edit_category', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('categories');
    $trail->push($category->title, route('admin.categories.edit', $category));
});
