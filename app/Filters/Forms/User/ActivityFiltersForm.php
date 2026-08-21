<?php

namespace App\Filters\Forms\User;

use App\Filters\Forms\Fields\DateRange;
use App\Filters\Forms\FilterForm;
use Kris\LaravelFormBuilder\Field;

class ActivityFiltersForm extends FilterForm
{
    public function buildForm(): void
    {
        $this
            ->add('type', Field::SELECT, [
                'label' => __('Type'),
                'choices' => $this->getTypes(),
                'wrapper' => ['class' => 'col-12 col-md'],
                'empty_value' => __('All'),
                'selected' => $this->request->string('type'),
            ])
            ->add('date', DateRange::NAME, [
                'label' => __('Date'),
                'wrapper' => ['class' => 'col-12 col-md'],
                'selected' => $this->request->input('date')
            ]);
    }

    private function getTypes () : array
    {
        return [
            'video_likes' => __('Liked videos'),
            'comment_likes' => __('Liked comments'),
            'video_dislikes' => __('Disliked videos'),
            'comment_dislikes' => __('Disliked comments'),
            'likes' => __('Likes'),
            'dislikes' => __('Dislikes'),
            'interactions' => __('Likes & Dislikes'),
            'comments' => __('Comments'),
        ];
    }
}
