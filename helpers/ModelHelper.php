<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Activity
 *
 * @property int $id
 * @property int $user_id
 * @property string $subject_type
 * @property int $subject_id
 * @property \Illuminate\Support\Carbon $perform_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @property-read mixed $type
 * @method static \Illuminate\Database\Eloquent\Builder|Activity filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity wherePerformAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperActivity {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string|null $icon
 * @property string|null $background
 * @property int $in_menu
 * @property int $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $background_url
 * @property-read mixed $route
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Video> $videos
 * @property-read int|null $videos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereBackground($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereInMenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCategory {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $video_id
 * @property int $user_id
 * @property int|null $parent_id
 * @property string $content
 * @property string $ip
 * @property string|null $banned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $0
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $dislikes
 * @property-read int|null $dislikes_count
 * @property-read mixed $dislikes_ratio
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $interactions
 * @property-read int|null $interactions_count
 * @property-read mixed $is_active
 * @property-read mixed $is_banned
 * @property-read mixed $is_long
 * @property-read mixed $is_pinned
 * @property-read mixed $is_reply
 * @property-read mixed $is_updated
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $likes
 * @property-read int|null $likes_count
 * @property-read mixed $likes_ratio
 * @property-read Comment|null $parent
 * @property-read mixed $parsed_content
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $replies
 * @property-read int|null $replies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $replies_interactions
 * @property-read int|null $replies_interactions_count
 * @property-read \App\Models\Report|null $reportByAuthUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read mixed $short_content
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Video $video
 * @method static \Database\Factories\CommentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Comment filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment public()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment replies()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereVideoId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperComment {}
}

namespace App\Models{
/**
 * App\Models\Export
 *
 * @property int $id
 * @property string|null $file
 * @property \App\Enums\ExportStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_completed
 * @property-read mixed $path
 * @method static \Illuminate\Database\Eloquent\Builder|Export newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Export newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Export query()
 * @method static \Illuminate\Database\Eloquent\Builder|Export whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Export whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Export whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Export whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Export whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperExport {}
}

namespace App\Models{
/**
 * App\Models\Interaction
 *
 * @property int $id
 * @property int $user_id
 * @property string $likeable_type
 * @property int $likeable_id
 * @property int $status
 * @property \Illuminate\Support\Carbon $perform_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $likeable
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\InteractionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereLikeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereLikeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction wherePerformAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperInteraction {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_read
 * @property-read mixed $message
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @property-read mixed $url
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification read()
 * @method static \Illuminate\Database\Eloquent\Builder|DatabaseNotification unread()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperNotification {}
}

namespace App\Models{
/**
 * App\Models\PasswordReset
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read mixed $is_expired
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPasswordReset {}
}

namespace App\Models\Pivots{
/**
 * App\Models\Pivots\FavoritePlaylist
 *
 * @property int $id
 * @property int $playlist_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $added_at
 * @method static \Illuminate\Database\Eloquent\Builder|FavoritePlaylist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FavoritePlaylist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FavoritePlaylist query()
 * @method static \Illuminate\Database\Eloquent\Builder|FavoritePlaylist whereAddedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FavoritePlaylist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FavoritePlaylist wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FavoritePlaylist whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFavoritePlaylist {}
}

namespace App\Models\Pivots{
/**
 * App\Models\Pivots\PlaylistVideo
 *
 * @property int $id
 * @property int $playlist_id
 * @property int $video_id
 * @property int $position
 * @method static \Database\Factories\Pivots\PlaylistVideoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistVideo wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistVideo wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistVideo whereVideoId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlaylistVideo {}
}

namespace App\Models\Pivots{
/**
 * App\Models\Pivots\Subscription
 *
 * @property int $id
 * @property int $subscriber_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $subscribe_at
 * @property \Illuminate\Support\Carbon $read_at
 * @property-read \App\Models\User|null $subscriber
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\Pivots\SubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereSubscribeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereSubscriberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSubscription {}
}

namespace App\Models{
/**
 * App\Models\Plan
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int $duration in month
 * @property string|null $stripe_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $period
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlan {}
}

namespace App\Models{
/**
 * App\Models\Playlist
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string|null $description
 * @property int $user_id
 * @property \App\Enums\PlaylistStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $first_video
 * @property-read mixed $is_active
 * @property-read mixed $route
 * @property-read mixed $type
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Video> $videos
 * @property-read int|null $videos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist active()
 * @method static \Database\Factories\PlaylistFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUuid($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlaylist {}
}

namespace App\Models{
/**
 * App\Models\Report
 *
 * @property int $id
 * @property int $user_id
 * @property string $reportable_type
 * @property int $reportable_id
 * @property \App\Enums\ReportReason $reason
 * @property string|null $comment
 * @property \App\Enums\ReportStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $icon
 * @property-read mixed $is_pending
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reportable
 * @property-read mixed $type
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ReportFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Report filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReport {}
}

namespace App\Models{
/**
 * App\Models\Search
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $query
 * @property \Illuminate\Support\Carbon $perform_at
 * @property-read \App\Models\Video|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Search newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Search newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Search query()
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search wherePerformAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereQuery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Search whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSearch {}
}

namespace App\Models{
/**
 * App\Models\Short
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $file
 * @property string $original_file_name
 * @property string $mimetype
 * @property-read string|null $duration
 * @property int|null $size size in bytes
 * @property int $user_id
 * @property int|null $category_id
 * @property \App\Enums\VideoStatus $status
 * @property string|null $language
 * @property \Illuminate\Support\Carbon|null $scheduled_date
 * @property \Illuminate\Support\Carbon|null $publication_date Date when video become public for the first time
 * @property int $allow_comments
 * @property string $default_comments_sort
 * @property int $show_likes
 * @property int|null $pinned_comment_id
 * @property \Illuminate\Support\Carbon|null $banned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $uploaded_at
 * @property string|null $youtube_id Youtube ID
 * @property int $is_short
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $comment_interactions
 * @property-read int|null $comment_interactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $description_is_long
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $dislikes
 * @property-read int|null $dislikes_count
 * @property-read mixed $dislikes_ratio
 * @property-read mixed $file_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $interactions
 * @property-read int|null $interactions_count
 * @property-read mixed $is_active
 * @property-read mixed $is_banned
 * @property-read mixed $is_created
 * @property-read mixed $is_draft
 * @property-read mixed $is_failed
 * @property-read mixed $is_pinned
 * @property-read mixed $is_planned
 * @property-read mixed $is_private
 * @property-read mixed $is_public
 * @property-read mixed $is_unlisted
 * @property-read mixed $is_uploading
 * @property-read mixed $language_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $likes
 * @property-read int|null $likes_count
 * @property-read mixed $likes_ratio
 * @property-read mixed $parsed_description
 * @property-read \App\Models\Comment|null $pinned_comment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist> $playlists
 * @property-read int|null $playlists_count
 * @property-read mixed $real_status
 * @property-read \App\Models\Report|null $reportByAuthUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read mixed $route
 * @property-read mixed $short_description
 * @property-read mixed $short_title
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subtitle> $subtitles
 * @property-read int|null $subtitles_count
 * @property-read \App\Models\Thumbnail|null $thumbnail
 * @property-read mixed $thumbnail_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Thumbnail> $thumbnails
 * @property-read int|null $thumbnails_count
 * @property-read mixed $type
 * @property-read \App\Models\Thumbnail|null $uploadedThumbnail
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\View> $views
 * @property-read int|null $views_count
 * @method static \Illuminate\Database\Eloquent\Builder|Video active()
 * @method static \Illuminate\Database\Eloquent\Builder|Video filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Short newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Short newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video notActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Video public($includeAuthVideo = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Short query()
 * @method static \Illuminate\Database\Eloquent\Builder|Video valid()
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereAllowComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereDefaultCommentsSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereIsShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereMimetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereOriginalFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short wherePinnedCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short wherePublicationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereScheduledDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereShowLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereUploadedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Short whereYoutubeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video withoutShorts()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperShort {}
}

namespace App\Models{
/**
 * App\Models\Subscription
 *
 * @property int $id
 * @property string $stripe_status
 * @property \Illuminate\Support\Carbon|null $next_payment
 * @property int|null $user_id
 * @property int|null $plan_id
 * @property string|null $stripe_id
 * @property \Illuminate\Support\Carbon|null $trial_ends_at
 * @property string $card_last4
 * @property \Illuminate\Support\Carbon $card_expired_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_active
 * @property-read mixed $is_canceled
 * @property-read mixed $is_trial_canceled
 * @property-read mixed $is_unpaid
 * @property-read mixed $on_trial
 * @property-read \App\Models\Plan|null $plan
 * @property-read mixed $status
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription active()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCardExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCardLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereNextPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStripeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSubscription {}
}

namespace App\Models{
/**
 * App\Models\Subtitle
 *
 * @property int $id
 * @property int $video_id
 * @property string $name
 * @property string $language
 * @property string $file
 * @property \App\Enums\SubtitleStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $file_url
 * @property-read mixed $is_public
 * @property-read mixed $language_name
 * @property-read \App\Models\Video $video
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle public()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtitle whereVideoId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSubtitle {}
}

namespace App\Models{
/**
 * App\Models\Thumbnail
 *
 * @property int $id
 * @property int $video_id
 * @property string|null $file
 * @property int $is_active
 * @property \App\Enums\ThumbnailStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $url
 * @property-read \App\Models\Video $video
 * @method static \Database\Factories\ThumbnailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail query()
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thumbnail whereVideoId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperThumbnail {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property string $stripe_id
 * @property int $amount amount in centimes
 * @property int $tax tax in centimes
 * @property int $fee stripe fee for transaction
 * @property \Illuminate\Support\Carbon $date
 * @property int|null $user_id
 * @property int|null $subscription_id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $postal_code
 * @property string $country
 * @property string|null $vat_id
 * @property-read mixed $amount_without_tax
 * @property-read mixed $formated_amount
 * @property-read mixed $invoice_name
 * @property-read mixed $invoice_path
 * @property-read mixed $invoice_url
 * @property-read mixed $route
 * @property-read \App\Models\Subscription|null $subscription
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereVatId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTransaction {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $username
 * @property string|null $slug
 * @property string $email
 * @property-write string $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $confirmation_token
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property string|null $last_login_ip
 * @property-read int|null $is_admin
 * @property string|null $remember_token
 * @property string|null $avatar
 * @property string|null $banner
 * @property string|null $description
 * @property string|null $country
 * @property string|null $website
 * @property int $show_subscribers
 * @property \Illuminate\Support\Carbon|null $banned_at
 * @property int|null $pinned_video_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $stripe_id
 * @property string|null $facebook_id
 * @property string|null $google_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activity
 * @property-read int|null $activity_count
 * @property-read mixed $avatar_url
 * @property-read mixed $banner_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $country_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $dislikes
 * @property-read int|null $dislikes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist> $favorites_playlist
 * @property-read int|null $favorites_playlist_count
 * @property-read mixed $first_active_video
 * @property-read mixed $has_current_subscription
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $interactions
 * @property-read int|null $interactions_count
 * @property-read mixed $is_active
 * @property-read mixed $is_banned
 * @property-read mixed $is_premium
 * @property-read mixed $json
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read mixed $others_active_videos
 * @property-read \App\Models\Video|null $pinned_video
 * @property-read mixed $plan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist> $playlists
 * @property-read int|null $playlists_count
 * @property-read \App\Models\Subscription|null $premium_subscription
 * @property-read \App\Models\Report|null $reportByAuthUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read mixed $route
 * @property-read mixed $short_description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Short> $shorts
 * @property-read int|null $shorts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $subscribers
 * @property-read int|null $subscribers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Video> $subscriptions_videos
 * @property-read int|null $subscriptions_videos_count
 * @property-read mixed $type
 * @property-read mixed $uploaded_videos
 * @property-read mixed $uploaded_videos_size
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $user_reports
 * @property-read int|null $user_reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $video_reports
 * @property-read int|null $video_reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Video> $videos
 * @property-read int|null $videos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $videos_comments
 * @property-read int|null $videos_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $videos_dislikes
 * @property-read int|null $videos_dislikes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $videos_interactions
 * @property-read int|null $videos_interactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $videos_likes
 * @property-read int|null $videos_likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\View> $videos_views
 * @property-read int|null $videos_views_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\View> $views
 * @property-read int|null $views_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Interaction[] $videos_comments_interactions
 * @property-read int|null $videos_comments_interactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User hasExpiredGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereConfirmationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePinnedVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereShowSubscribers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWebsite($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * App\Models\Video
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $file
 * @property string $original_file_name
 * @property string $mimetype
 * @property-read string|null $duration
 * @property int|null $size size in bytes
 * @property int $user_id
 * @property int|null $category_id
 * @property \App\Enums\VideoStatus $status
 * @property string|null $language
 * @property \Illuminate\Support\Carbon|null $scheduled_date
 * @property \Illuminate\Support\Carbon|null $publication_date Date when video become public for the first time
 * @property int $allow_comments
 * @property string $default_comments_sort
 * @property int $show_likes
 * @property int|null $pinned_comment_id
 * @property \Illuminate\Support\Carbon|null $banned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $uploaded_at
 * @property string|null $youtube_id Youtube ID
 * @property int $is_short
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $comment_interactions
 * @property-read int|null $comment_interactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $description_is_long
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $dislikes
 * @property-read int|null $dislikes_count
 * @property-read mixed $dislikes_ratio
 * @property-read mixed $file_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $interactions
 * @property-read int|null $interactions_count
 * @property-read mixed $is_active
 * @property-read mixed $is_banned
 * @property-read mixed $is_created
 * @property-read mixed $is_draft
 * @property-read mixed $is_failed
 * @property-read mixed $is_pinned
 * @property-read mixed $is_planned
 * @property-read mixed $is_private
 * @property-read mixed $is_public
 * @property-read mixed $is_unlisted
 * @property-read mixed $is_uploading
 * @property-read mixed $language_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $likes
 * @property-read int|null $likes_count
 * @property-read mixed $likes_ratio
 * @property-read mixed $parsed_description
 * @property-read \App\Models\Comment|null $pinned_comment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist> $playlists
 * @property-read int|null $playlists_count
 * @property-read mixed $real_status
 * @property-read \App\Models\Report|null $reportByAuthUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read mixed $route
 * @property-read mixed $short_description
 * @property-read mixed $short_title
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subtitle> $subtitles
 * @property-read int|null $subtitles_count
 * @property-read \App\Models\Thumbnail|null $thumbnail
 * @property-read mixed $thumbnail_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Thumbnail> $thumbnails
 * @property-read int|null $thumbnails_count
 * @property-read mixed $type
 * @property-read \App\Models\Thumbnail|null $uploadedThumbnail
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\View> $views
 * @property-read int|null $views_count
 * @method static \Illuminate\Database\Eloquent\Builder|Video active()
 * @method static \Database\Factories\VideoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Video filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Video newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video notActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Video public($includeAuthVideo = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Video query()
 * @method static \Illuminate\Database\Eloquent\Builder|Video valid()
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereAllowComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDefaultCommentsSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereIsShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereMimetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereOriginalFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video wherePinnedCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video wherePublicationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereScheduledDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereShowLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUploadedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereYoutubeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video withoutShorts()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperVideo {}
}

namespace App\Models{
/**
 * App\Models\View
 *
 * @property int $id
 * @property int $video_id
 * @property int|null $user_id
 * @property string $ip
 * @property \Illuminate\Support\Carbon $view_at
 * @property-read \App\Models\Video|null $user
 * @property-read \App\Models\Video $video
 * @method static \Database\Factories\ViewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|View filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|View newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|View newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|View query()
 * @method static \Illuminate\Database\Eloquent\Builder|View whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|View whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|View whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|View whereVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|View whereViewAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperView {}
}

