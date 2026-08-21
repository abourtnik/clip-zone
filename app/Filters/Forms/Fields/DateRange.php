<?php

namespace App\Filters\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class DateRange extends FormField {

    public const string NAME = 'date_range';

    protected function getTemplate() : string
    {
        return 'forms.fields.date_range';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true): string
    {
        $options['options'] = \App\Enums\DateRange::get();

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
