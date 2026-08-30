<?php

namespace App\Filters\Forms;

use Kris\LaravelFormBuilder\Form;

class FilterForm extends Form
{
    protected $formOptions = [
        'method' => 'GET',
        'url' => null,
        'attr' => [
            'class' => 'mb-4 row align-items-end gx-2 gy-2',
            'x-show.important' => 'filters'
        ],
        'template' => 'forms.filter_form',
    ];

    /** @param class-string<\BackedEnum> $enumClass */
    protected function enumFromRequest(string $key, string $enumClass): int|string|null
    {
        $value = $this->request->input($key);

        return is_numeric($value) ? $enumClass::tryFrom((int) $value)?->value : null;
    }
}
