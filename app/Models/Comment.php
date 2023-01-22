<?php

namespace App\Models;

use App\Models\Interfaces\Likeable;
use App\Models\Traits\HasLike;
use Illuminate\Database\Eloquent\Builder;
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

class Comment extends Model implements Likeable
{
    use HasLike, LogsActivity;

    protected $guarded = ['id'];

    protected static $recordEvents = ['created'];

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
            get: fn () => Str::length($this->content) > 780,
        );
    }

    protected function shortContent(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->content, 780,' ...'),
        );
    }

    /**
     * -------------------- SCOPES --------------------
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
