<?php

namespace App\Filters\Forms\Admin;

use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class UserFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('search', Field::SEARCH, [
                'label' => $this->getName(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-6 col-xl col-xxl-3'],
                'attr' => [
                    'placeholder' => 'Search'
                ],
                'value' => $this->request->get('search')
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => 'Registration date start',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-6 col-xl'],
                'value' => $this->request->get('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => 'Registration date end',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-6 col-xl'],
                'value' => $this->request->get('date_end')
            ])
            ->add('status', Field::SELECT, [
                'label' => 'Status',
                'choices' => $this->getStatus(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-6 col-xl'],
                'empty_value' => 'All',
                'selected' => $this->request->get('status'),
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
