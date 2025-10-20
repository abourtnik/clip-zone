<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @mixin IdeHelperCategory
 */
class Category extends Model implements Sortable
{
    use SortableTrait;

    protected $guarded = ['id'];

    public const string IMAGE_FOLDER = 'categories';

    public function videos () : HasMany {
        return $this->hasMany(Video::class);
    }

    protected function backgroundUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->background ? Storage::url(self::IMAGE_FOLDER.'/'.$this->background) : '/images/default_banner.jpg'
        );
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn () => route('pages.category', $this->slug)
        );
    }
}
