<?php

namespace App\Filters\Forms\User;

use App\Enums\VideoStatus;
use App\Filters\Forms\Fields\DateRange;
use App\Filters\Forms\FilterForm;
use App\Models\Category;
use Kris\LaravelFormBuilder\Field;

class VideoFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('search', Field::SEARCH, [
                'label' => __('Search'),
                'wrapper' => ['class' => 'col-12 col-sm-12 col-md-12 col-lg col-xl'],
                'attr' => [
                    'placeholder' => __('Search')
                ],
                'value' => $this->request->string('search')
            ])
            ->add('status', Field::SELECT, [
                'label' => __('Status'),
                'choices' => VideoStatus::getAll(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->enum('status', VideoStatus::class)?->value,
            ])
            ->add('category', Field::SELECT, [
                'label' => __('Category'),
                'choices' => $this->getCategories(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->string('category'),
            ])
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'selected' => $this->request->input('date')
            ]);
    }

    private function getCategories(): array
    {
        $categories = Category::all()
            ->pluck('title', 'id')
            ->transform(function ($title) {
                return __($title);
            });

        $categories->prepend(__('Without categories'), 'without');

        return $categories->toArray();
    }
}
