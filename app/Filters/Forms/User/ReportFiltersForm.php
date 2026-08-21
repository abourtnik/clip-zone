<?php

namespace App\Filters\Forms\User;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Filters\Forms\Fields\DateRange;
use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class ReportFiltersForm extends FilterForm
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
            ->add('type', Field::SELECT, [
                'label' => 'Type',
                'choices' => $this->getTypes(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->string('type'),
            ])
            ->add('reason', Field::SELECT, [
                'label' => __('Reason'),
                'choices' => ReportReason::get(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->enum('reason', ReportReason::class)?->value,
            ])
            ->add('status', Field::SELECT, [
                'label' => 'Status',
                'choices' => ReportStatus::get(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->enum('status', ReportStatus::class)?->value,
            ])
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'selected' => $this->request->input('date')
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
