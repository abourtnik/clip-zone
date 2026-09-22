<?php

namespace App\Filters\Forms;

use Kris\LaravelFormBuilder\Field;

class SearchFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('q', Field::HIDDEN, [
                'value' => $this->request->string('q')
            ])
            ->add('type', Field::SELECT, [
                'label' => __('Type'),
                'choices' => [
                    'video' => __('Video'),
                    'user' => __('User'),
                ],
                'wrapper' => ['class' => 'col-12 col-sm-6 col-lg'],
                'empty_value' => 'All',
                'selected' => $this->request->string('type'),
            ])
            ->add('date', Field::SELECT, [
                'label' => __('Upload Date'),
                'choices' => [
                    'hour' => __('Last hour'),
                    'today' => __('Today'),
                    'week' => __('This week'),
                    'month' => __('This month'),
                    'year' => __('This year')
                ],
                'wrapper' => ['class' => 'col-12 col-sm-6 col-lg'],
                'empty_value' => 'All',
                'selected' => $this->request->string('date'),
            ])
            ->add('duration', Field::SELECT, [
                'label' => __('Duration'),
                'choices' => [
                    '4' => __('Under 4 minutes'),
                    '4-20' => __('4-20 minutes'),
                    '20' => __('Over 20 minutes')
                ],
                'wrapper' => ['class' => 'col-12 col-sm-6 col-lg'],
                'empty_value' => 'All',
                'selected' => $this->request->string('duration'),
            ]);
    }
}
