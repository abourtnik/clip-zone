<?php

namespace App\Filters\Forms\User;

use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class SubscriberFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('search', Field::SEARCH, [
                'label' => $this->getName(),
                'wrapper' => ['class' => 'col-12 col-lg'],
                'attr' => [
                    'placeholder' => 'Search'
                ],
                'value' => $this->request->get('search')
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => 'Subscription date start',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-lg'],
                'value' => $this->request->get('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => 'Subscription date end',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-lg'],
                'value' => $this->request->get('date_end')
            ]);
    }
}
