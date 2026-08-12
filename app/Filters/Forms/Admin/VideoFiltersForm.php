<?php

namespace App\Filters\Forms\Admin;

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
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-2'],
                'attr' => [
                    'placeholder' => 'Search'
                ],
                'value' => $this->request->string('search')
            ])
            ->add('status', Field::SELECT, [
                'label' => 'Status',
                'choices' => VideoStatus::getAll(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-1'],
                'empty_value' => 'All',
                'selected' => $this->request->enum('status', VideoStatus::class)?->value,
            ])
            ->add('category', Field::ENTITY, [
                'class' => Category::class,
                'property' => 'title',
                'property_key' => 'id',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-2'],
                'empty_value' => 'All',
                'selected' => $this->request->string('category'),
            ])
            ->add('user', 'autocomplete', [
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-2'],
                'endpoint' => route('admin.search.users')
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => 'Publication date start',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-2'],
                'value' => $this->request->date('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => 'Publication date start',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-2'],
                'value' => $this->request->date('date_end')
            ]);
    }
}
