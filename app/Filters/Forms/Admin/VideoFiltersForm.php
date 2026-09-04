<?php

namespace App\Filters\Forms\Admin;

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
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'attr' => [
                    'placeholder' => __('Search')
                ],
                'value' => $this->request->string('search')
            ])
            ->add('status', Field::SELECT, [
                'label' => __('Status'),
                'choices' =>$this->getVideoStatus(),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->getSelectedStatus(),
            ])
            ->add('category', Field::ENTITY, [
                'label' => __('Category'),
                'class' => Category::class,
                'property' => 'title',
                'property_key' => 'id',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'empty_value' => __('All'),
                'selected' => $this->request->string('category'),
            ])
            ->add('user', 'autocomplete', [
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'endpoint' => route('admin.search.users')
            ])
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-md-6 col-lg col-xl'],
                'selected' => $this->request->input('date')
            ]);
    }

    private function getVideoStatus(): array
    {
        return collect(VideoStatus::getAll())
            ->merge([
                'deleted' => __('Deleted'),
            ])
            ->toArray();
    }

    private function getSelectedStatus(): int|string|null
    {
        $value = $this->request->input('status');

        if ($value === 'deleted') {
            return 'deleted';
        }

        return $this->enumFromRequest('status', VideoStatus::class);
    }
}
