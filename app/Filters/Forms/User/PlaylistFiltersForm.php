<?php

namespace App\Filters\Forms\User;

use App\Enums\PlaylistStatus;
use App\Filters\Forms\Fields\DateRange;
use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class PlaylistFiltersForm extends FilterForm
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
            ->add('status', Field::SELECT, [
                'label' => __('Status'),
                'choices' => PlaylistStatus::get(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->string('status'),
            ])
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'selected' => $this->request->input('date')
            ]);
    }
}
