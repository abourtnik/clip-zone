<?php

namespace App\Filters\Forms\Admin;

use App\Filters\Forms\Fields\DateRange;
use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class UserFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('search', Field::SEARCH, [
                'label' => $this->getName(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'attr' => [
                    'placeholder' => 'Search'
                ],
                'value' => $this->request->string('search')
            ])
            ->add('status', Field::SELECT, [
                'label' => 'Status',
                'choices' => $this->getStatus(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => 'All',
                'selected' => $this->request->string('status'),
            ])
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'selected' => $this->request->input('date')
            ]);
    }

    private function getStatus (): array
    {
        return [
            'banned' => 'Banned',
            'unverified' => 'Unverified',
            'premium' => 'Premium'
        ];
    }
}
