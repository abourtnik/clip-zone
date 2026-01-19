<?php

namespace App\Filters\Forms\User;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class ReportFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('search', Field::SEARCH, [
                'label' => __('Search'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl col-xxl-3'],
                'attr' => [
                    'placeholder' => __('Search')
                ],
                'value' => $this->request->get('search')
            ])
            ->add('type', Field::SELECT, [
                'label' => 'Type',
                'choices' => $this->getTypes(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->get('type'),
            ])
            ->add('reason', Field::SELECT, [
                'label' => __('Reason'),
                'choices' => ReportReason::get(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->get('reason'),
            ])
            ->add('status', Field::SELECT, [
                'label' => 'Status',
                'choices' => ReportStatus::get(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->get('status'),
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => __('Report date start'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'value' => $this->request->get('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => __('Report date end'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'value' => $this->request->get('date_end')
            ]);
    }

    private function getTypes () : array
    {
        return [
            'video' => __('Video'),
            'comment' => __('Comment'),
            'user' => __('User')
        ];
    }
}
