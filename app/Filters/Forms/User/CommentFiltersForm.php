<?php

namespace App\Filters\Forms\User;

use App\Filters\Forms\Fields\DateRange;
use App\Filters\Forms\FilterForm;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\Field;

class CommentFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('search', Field::SEARCH, [
                'label' => __('Search'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'attr' => [
                    'placeholder' => __('Search')
                ],
                'value' => $this->request->string('search')
            ])
            ->add('video', Field::ENTITY, [
                'class' => Video::class,
                'label' => __('Video'),
                'property' => 'title',
                'property_key' => 'id',
                'query_builder' => function (Video $video) {
                    return $video->where('user_id', Auth::user()->id);
                },
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->integer('video'),
            ])
            ->add('user', 'autocomplete', [
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'endpoint' => route('search.users')
            ])
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'selected' => $this->request->input('date')
            ]);
    }
}
