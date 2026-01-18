<?php

namespace App\Filters\Forms\User;

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
                'wrapper' => ['class' => 'col-12 col-lg'],
                'empty_value' => __('All'),
                'selected' => $this->request->get('type'),
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => __('Date start'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-lg'],
                'value' => $this->request->get('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => __('Date end'),
                'wrapper' => ['class' => 'col-12 col-sm-6 col-lg'],
                'value' => $this->request->get('date_end')
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
