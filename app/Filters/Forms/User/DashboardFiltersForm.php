<?php

namespace App\Filters\Forms\User;

use App\Filters\Forms\Fields\DateRange;
use App\Filters\Forms\FilterForm;

class DashboardFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-sm'],
                'selected' => $this->request->input('date'),
            ]);
    }
}
