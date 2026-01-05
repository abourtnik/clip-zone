<?php

namespace App\Filters\Forms\User;

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
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl col-xxl-3'],
                'attr' => [
                    'placeholder' => __('Search')
                ],
                'value' => $this->request->get('search')
            ])
            ->add('video', Field::ENTITY, [
                'class' => Video::class,
                'label' => __('Video'),
                'property' => 'title',
                'property_key' => 'id',
                'query_builder' => function (Video $video) {
                    return $video->where('user_id', Auth::user()->id);
                },
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->get('video'),
            ])
            ->add('user', 'autocomplete', [
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'endpoint' => route('search.users')
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => __('Publication date start'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'value' => $this->request->get('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => __('Publication date end'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'value' => $this->request->get('date_end')
            ]);
    }
}
