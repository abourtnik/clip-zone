<?php

namespace App\Filters\Forms\Admin;

use App\Filters\Forms\Fields\DateRange;
use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class CommentFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('search', Field::SEARCH, [
                'label' => __('Search'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'attr' => [
                    'placeholder' => __('Search')
                ],
                'value' => $this->request->string('search')
            ])
            ->add('video', 'autocomplete', [
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'endpoint' => route('admin.search.videos')
            ])
            ->add('user', 'autocomplete', [
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'endpoint' => route('admin.search.users')
            ])
            ->add('replies', Field::SELECT, [
                'label' => __('Replies'),
                'choices' => [
                    'with' => __('With replies'),
                    'without' => __('Without replies')
                ],
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->string('replies'),
            ])
            ->add('ban', Field::SELECT, [
                'label' => __('Banned'),
                'choices' => [
                    'banned' => __('Banned'),
                    'not_banned' => __('Not banned'),
                ],
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->string('ban'),
            ])
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'selected' => $this->request->input('date')
            ]);
    }
}
