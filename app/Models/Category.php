<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function backgroundUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->background ? asset('storage/categories/'. $this->background) : '/images/default_banner.jpg'
        );
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn () => route('pages.category', $this->slug)
        );
    }
}
