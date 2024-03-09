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
}
