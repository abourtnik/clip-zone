<?php

namespace App\Filters\Forms\User;

use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class DashboardFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => __('Publication date start'),
                'wrapper' => ['class' => 'col-12 col-sm'],
                'value' => $this->request->get('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => __('Publication date start'),
                'wrapper' => ['class' => 'col-12 col-sm'],
                'value' => $this->request->get('date_end')
            ]);
    }
}
