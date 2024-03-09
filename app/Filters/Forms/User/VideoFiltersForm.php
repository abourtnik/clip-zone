<?php

namespace App\Filters\Forms\User;

use App\Enums\VideoStatus;
use App\Filters\Forms\FilterForm;
use App\Models\Category;
use Kris\LaravelFormBuilder\Field;

class VideoFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('search', Field::SEARCH, [
                'label' => $this->getName(),
                'wrapper' => ['class' => 'col-12 col-sm-12 col-md-12 col-lg-4 col-xl col-xxl-3'],
                'attr' => [
                    'placeholder' => 'Search'
                ],
                'value' => $this->request->get('search')
            ])
            ->add('status', Field::SELECT, [
                'label' => 'Status',
                'choices' => VideoStatus::getAll(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => 'All',
                'selected' => $this->request->get('status'),
            ])
            ->add('category', Field::SELECT, [
                'label' => 'Category',
                'choices' => $this->getCategories(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-4 col-xl'],
                'empty_value' => 'All',
                'selected' => $this->request->get('category'),
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

    private function getCategories(): array
    {
        $categories = Category::all()
            ->pluck('title', 'id')
            ->toArray();

        return array_merge([
            'without' => 'Without categories'
        ], $categories);
    }
}
