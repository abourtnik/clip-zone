<?php

namespace App\Filters\Forms\User;

use App\Enums\PlaylistStatus;
use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class PlaylistFiltersForm extends FilterForm
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
            ->add('status', Field::SELECT, [
                'label' => 'Status',
                'choices' => PlaylistStatus::get(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-6 col-xl'],
                'empty_value' => 'All',
                'selected' => $this->request->get('status'),
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => 'Publication date start',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-6 col-xl'],
                'value' => $this->request->get('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => 'Publication date start',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-6 col-xl'],
                'value' => $this->request->get('date_end')
            ]);
    }
}
