<?php

namespace App\Filters\Forms\Admin;

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
                'label' => $this->getName(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'attr' => [
                    'placeholder' => 'Search'
                ],
                'value' => $this->request->string('search')
            ])
            ->add('user', 'autocomplete', [
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'endpoint' => route('admin.search.users')
            ])
            ->add('type', Field::SELECT, [
                'label' => 'Type',
                'choices' => $this->getTypes(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => 'All',
                'selected' => $this->request->string('type'),
            ])
            ->add('reason', Field::SELECT, [
                'label' => 'Reason',
                'choices' => ReportReason::get(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => 'All',
                'selected' => $this->request->enum('reason', ReportReason::class),
            ])
            ->add('status', Field::SELECT, [
                'label' => 'Status',
                'choices' => ReportStatus::get(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => 'All',
                'selected' => $this->request->enum('status', ReportStatus::class),
            ])
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'selected' => $this->request->input('date')
            ]);
    }

    private function getTypes () : array
    {
        return [
            'video' => 'Video',
            'comment' => 'Comment',
            'user' => 'User'
        ];
    }
}
