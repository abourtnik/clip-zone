<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Category extends Model implements Sortable
{
    use SortableTrait;

    protected $guarded = ['id'];

    public function videos () : HasMany {
        return $this->hasMany(Video::class);
    }
}
