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
 * @property int $id
 * @property int $user_id
 * @property string $subject_type
 * @property int $subject_id
 * @property \Illuminate\Support\Carbon $perform_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @property-read mixed $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity wherePerformAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Activity whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperActivity {}
}

namespace App\Models{
/**
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereBackground($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereInMenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCategory {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $video_id
 * @property int $user_id
 * @property int|null $parent_id
 * @property string $content
 * @property string $ip
 * @property \Illuminate\Support\Carbon|null $banned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment replies()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperComment {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $tokenable_type
 * @property int $tokenable_id
 * @property string $name
 * @property string|null $type
 * @property string $token
 * @property string|null $abilities
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereTokenableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Device whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDevice {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $file
 * @property int|null $size size in bytes
 * @property \App\Enums\ExportStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_completed
 * @property-read mixed $path
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperExport {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $likeable_type
 * @property int $likeable_id
 * @property bool $status
 * @property \Illuminate\Support\Carbon $perform_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $likeable
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\InteractionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction whereLikeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction whereLikeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction wherePerformAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interaction whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperInteraction {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array<array-key, mixed> $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_read
 * @property-read mixed $message
 * @property-read \Illuminate\Database\Eloquent\Model $notifiable
 * @property-read mixed $url
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification read()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperNotification {}
}

namespace App\Models\Pivots{
/**
 * @property int $id
 * @property int $playlist_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $added_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FavoritePlaylist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FavoritePlaylist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FavoritePlaylist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FavoritePlaylist whereAddedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FavoritePlaylist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FavoritePlaylist wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FavoritePlaylist whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFavoritePlaylist {}
}

namespace App\Models\Pivots{
/**
 * @property int $id
 * @property int $playlist_id
 * @property int $video_id
 * @property int $position
 * @property \Illuminate\Support\Carbon $added_at
 * @method static \Database\Factories\Pivots\PlaylistVideoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaylistVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaylistVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaylistVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaylistVideo whereAddedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaylistVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaylistVideo wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaylistVideo wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaylistVideo whereVideoId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlaylistVideo {}
}

namespace App\Models\Pivots{
/**
 * @property int $id
 * @property int $subscriber_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $subscribe_at
 * @property \Illuminate\Support\Carbon $read_at
 * @property-read \App\Models\User|null $subscriber
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\Pivots\SubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereSubscribeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereSubscriberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSubscription {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|false $price in centimes
 * @property int $duration in month
 * @property string|null $stripe_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $period
 * @method static \Database\Factories\PlanFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlan {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string|null $description
 * @property int $user_id
 * @property \App\Enums\PlaylistStatus $status
 * @property \App\Enums\PlaylistSort $sort
 * @property bool $is_deletable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $first_video
 * @property-read mixed $is_active
 * @property-read mixed $route
 * @property-read mixed $type
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Pivots\PlaylistVideo|\App\Models\Pivots\FavoritePlaylist|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Video> $videos
 * @property-read int|null $videos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist active()
 * @method static \Database\Factories\PlaylistFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereIsDeletable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlaylist {}
}

namespace App\Models{
/**
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReportableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReportableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReport {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $query
 * @property string|null $ip
 * @property string|null $lang
 * @property int $results
 * @property \Illuminate\Support\Carbon $perform_at
 * @property-read \App\Models\Video|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search wherePerformAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search whereQuery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search whereResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Search whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSearch {}
}

namespace App\Models{
/**
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription active()
 * @method static \Database\Factories\SubscriptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCardExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCardLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereNextPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereStripeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSubscription {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $video_id
 * @property string $name
 * @property string $language
 * @property string $file
 * @property \App\Enums\SubtitleStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $file_url
 * @property-read mixed $is_public
 * @property-read mixed $language_name
 * @property-read \App\Models\Video $video
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subtitle whereVideoId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSubtitle {}
}

namespace App\Models{
/**
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thumbnail whereVideoId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperThumbnail {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $stripe_id
 * @property string|false $amount amount in centimes
 * @property string|false $tax tax in centimes
 * @property string|false $fee stripe fee for transaction
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
 * @property-read mixed $invoice_name
 * @property-read mixed $invoice_path
 * @property-read mixed $invoice_url
 * @property-read mixed $route
 * @property-read \App\Models\Subscription|null $subscription
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereVatId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTransaction {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $username
 * @property string|null $slug
 * @property string $email
 * @property string|null $temporary_email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property string|null $last_login_ip
 * @property int|null $is_admin
 * @property string|null $remember_token
 * @property string|null $avatar
 * @property string|null $banner
 * @property string|null $description
 * @property string|null $country
 * @property string|null $website
 * @property bool $show_subscribers
 * @property \Illuminate\Support\Carbon|null $banned_at
 * @property int|null $pinned_video_id
 * @property string|null $facebook_id
 * @property string|null $google_id
 * @property string|null $stripe_id
 * @property \Propaganistas\LaravelPhone\PhoneNumber|null $phone
 * @property \Illuminate\Support\Carbon|null $phone_verified_at
 * @property string|null $phone_confirmation_code
 * @property string $language
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activity
 * @property-read int|null $activity_count
 * @property-read mixed $avatar_url
 * @property-read mixed $banner_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $country_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Device> $devices
 * @property-read int|null $devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $dislikes
 * @property-read int|null $dislikes_count
 * @property-read \App\Models\Pivots\Subscription|\App\Models\Pivots\FavoritePlaylist|null $pivot
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $subscribers
 * @property-read int|null $subscribers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Video> $subscriptions_videos
 * @property-read int|null $subscriptions_videos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $videos_comments_interactions
 * @property-read int|null $videos_comments_interactions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User hasExpiredGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneConfirmationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePinnedVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereShowSubscribers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTemporaryEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $file
 * @property string $original_file_name
 * @property string|null $original_mimetype
 * @property string|null $duration
 * @property int|null $size size in bytes
 * @property int $user_id
 * @property int|null $category_id
 * @property \App\Enums\VideoStatus $status
 * @property string|null $language
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $published_at Date when video become public for the first time
 * @property bool $allow_comments
 * @property string $default_comments_sort
 * @property bool $show_likes
 * @property int|null $pinned_comment_id
 * @property \Illuminate\Support\Carbon|null $banned_at
 * @property \Illuminate\Support\Carbon|null $uploaded_at
 * @property string|null $youtube_id Youtube ID
 * @property int $is_live
 * @property int $is_short
 * @property int $views
 * @property bool $show_ad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $comment_interactions
 * @property-read int|null $comment_interactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $date
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
 * @property-read mixed $is_uploaded
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\View> $viewsHistory
 * @property-read int|null $views_history_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video active()
 * @method static \Database\Factories\VideoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video notActive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video public(bool $includeAuthVideo = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video valid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereAllowComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereDefaultCommentsSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereIsLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereIsShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereOriginalFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereOriginalMimetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video wherePinnedCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereShowAd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereShowLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereUploadedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video whereYoutubeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video withoutShorts()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Video withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperVideo {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $video_id
 * @property int|null $user_id
 * @property string $ip
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon $view_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Video $video
 * @method static \Database\Factories\ViewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View filter(array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View whereVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View whereViewAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|View withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperView {}
}

