<?php

namespace App\Filters\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class Autocomplete extends FormField {

    protected function getTemplate() : string
    {
        return 'forms.fields.autocomplete';
    }
}
