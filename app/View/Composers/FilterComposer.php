<?php

namespace App\View\Composers;

use Illuminate\Support\Str;
use Illuminate\View\View;

class FilterComposer
{
    private const FILTERS = ['user', 'video'];

    /**
     * Bind data to the view.
     *
     * @param View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        foreach (self::FILTERS as $filter) {
            if (request()->has($filter)){
                $class = 'App\Models\\' .Str::ucfirst($filter);
                $model = $class::query()->find(request()->get($filter));
                if ($model) {
                    $resource = 'App\Http\Resources\\' .Str::ucfirst($filter). '\\' .Str::ucfirst($filter). 'SearchResource';
                    $view->with('selected'.Str::ucfirst($filter), (new $resource($model))->toJson());
                }
            }
        }
    }
}
