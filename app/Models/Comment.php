<?php

namespace App\Models;

use App\Models\Interfaces\Likeable;
use App\Models\Traits\HasLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model implements Likeable
{
    use HasLike;

    protected $guarded = ['id'];

    use HasFactory;

    public function video () : BelongsTo {
        return $this->belongsTo(Video::class);
    }

    public function user () : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function replies () : HasMany  {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }
}
