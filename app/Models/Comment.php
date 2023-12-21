<?php

namespace App\Models;

use App\Helpers\Parser;
use App\Models\Interfaces\Likeable;
use App\Models\Interfaces\Reportable;
use App\Models\Traits\HasLike;
use App\Models\Traits\HasReport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Comment extends Model implements Likeable, Reportable
{
    use HasLike, LogsActivity, HasReport, HasEagerLimit;

    protected $guarded = ['id'];

    protected static $recordEvents = ['created'];

    protected $dates = [
        'banned_at'
    ];

    use HasFactory;

    /**
     * -------------------- RELATIONS --------------------
     */

    public function video () : BelongsTo {
        return $this->belongsTo(Video::class);
    }

    public function user () : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function replies () : HasMany  {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    public function parent () : HasOne  {
        return $this->hasOne(Comment::class, 'id', 'parent_id');
    }

    public function replies_interactions () : HasManyThrough  {
        return $this->hasManyThrough(Interaction::class, Comment::class, 'parent_id', 'likeable_id')
            ->where('likeable_type', Comment::class);

    }

    /**
     * -------------------- ATTRIBUTES --------------------
     */

    protected function isLong(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::length($this->content) > 780
        );
    }

    protected function isReply(): Attribute
    {
        return Attribute::make(
            get: fn () => !is_null($this->parent_id)
        );
    }

    protected function isPinned(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->video->pinned_comment?->is($this)
        );
    }

    protected function isBanned(): Attribute
    {
        return Attribute::make(
            get: fn () => !is_null($this->banned_at)
        );
    }

    protected function isUpdated(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->ne($this->updated_at)
        );
    }

    protected function shortContent(): Attribute
    {
        return Attribute::make(
            get: fn () => Parser::applyParsers(Str::limit($this->content, 780), ['links', 'timecodes'])
        );
    }

    protected function parsedContent(): Attribute
    {
        return Attribute::make(
            get: fn () => Parser::applyParsers($this->content, ['links', 'timecodes'])
        );
    }

    /**
     * -------------------- SCOPES --------------------
     */

    /**
     * Scope a query to only include replies.
     *
     * @param  Builder $query
     * @return void
     */
    public function scopeReplies(Builder $query): void
    {
        $query->whereNotNull('parent_id');
    }

    /**
     * Scope a query to only include active videos.
     *
     * @param QueryBuilder|EloquentBuilder $query
     * @return QueryBuilder|EloquentBuilder
     */
    public function scopePublic(QueryBuilder|EloquentBuilder $query): QueryBuilder|EloquentBuilder
    {
        return $query->whereNull('banned_at');
    }

    /**
     * Scope a query to only include replies.
     *
     * @param  Builder $query
     * @return void
     */
    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * -------------------- METHODS --------------------
     */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }




}
