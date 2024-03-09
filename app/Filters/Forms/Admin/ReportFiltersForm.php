<?php

namespace App\Filters\Forms\Admin;

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
                'label' => $this->getName(),
                'wrapper' => ['class' => 'col'],
                'attr' => [
                    'placeholder' => 'Search'
                ],
                'value' => $this->request->get('search')
            ])
            ->add('user', 'autocomplete', [
                'wrapper' => ['class' => 'col'],
                'endpoint' => route('admin.search.users')
            ])
            ->add('type', Field::SELECT, [
                'label' => 'Type',
                'choices' => $this->getTypes(),
                'wrapper' => ['class' => 'col'],
                'empty_value' => 'All',
                'selected' => $this->request->get('type'),
            ])
            ->add('reason', Field::SELECT, [
                'label' => 'Reason',
                'choices' => ReportReason::get(),
                'wrapper' => ['class' => 'col'],
                'empty_value' => 'All',
                'selected' => $this->request->get('reason'),
            ])
            ->add('status', Field::SELECT, [
                'label' => 'Status',
                'choices' => ReportStatus::get(),
                'wrapper' => ['class' => 'col'],
                'empty_value' => 'All',
                'selected' => $this->request->get('status'),
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => 'Report date start',
                'wrapper' => ['class' => 'col'],
                'value' => $this->request->get('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => 'Report date end',
                'wrapper' => ['class' => 'col'],
                'value' => $this->request->get('date_end')
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
