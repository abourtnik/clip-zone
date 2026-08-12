<?php

namespace App\Filters\Forms\Admin;

use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class CommentFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('search', Field::SEARCH, [
                'label' => $this->getName(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 col-xxl-1'],
                'attr' => [
                    'placeholder' => 'Search'
                ],
                'value' => $this->request->string('search')
            ])
            ->add('video', 'autocomplete', [
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 col-xxl-2'],
                'endpoint' => route('admin.search.videos')
            ])
            ->add('user', 'autocomplete', [
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 col-xxl-2'],
                'endpoint' => route('admin.search.users')
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => 'Comment date start',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 col-xxl-2'],
                'value' => $this->request->date('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => 'Comment date end',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 col-xxl-2'],
                'value' => $this->request->date('date_end')
            ])
            ->add('replies', Field::SELECT, [
                'label' => 'Replies',
                'choices' => [
                    'with' => 'With replies',
                    'without' => 'Without replies',
                ],
                'wrapper' => ['class' => 'col-12 col-lg'],
                'empty_value' => 'All',
                'selected' => $this->request->string('replies'),
            ])
            ->add('ban', Field::SELECT, [
                'label' => 'Banned',
                'choices' => [
                    'banned' => 'Banned',
                    'not_banned' => 'Not banned',
                ],
                'wrapper' => ['class' => 'col-12 col-lg'],
                'empty_value' => 'All',
                'selected' => $this->request->string('ban'),
            ]);
    }
}
