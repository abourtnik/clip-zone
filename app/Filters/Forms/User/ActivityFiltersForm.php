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
                'label' => 'Type',
                'choices' => $this->getTypes(),
                'wrapper' => ['class' => 'col-12 col-lg'],
                'empty_value' => 'All',
                'selected' => $this->request->get('type'),
            ])
            ->add('date_start', Field::DATETIME_LOCAL, [
                'label' => 'Date start',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-lg'],
                'value' => $this->request->get('date_start')
            ])
            ->add('date_end', Field::DATETIME_LOCAL, [
                'label' => 'Date end',
                'wrapper' => ['class' => 'col-12 col-sm-6 col-lg'],
                'value' => $this->request->get('date_end')
            ]);
    }

    private function getTypes () : array
    {
        return [
            'video_likes' => 'Video Likes',
            'comment_likes' => 'Comment Likes',
            'video_dislikes' => 'Video Dislikes',
            'comment_dislikes' => 'Comment Dislikes',
            'likes' => 'Likes',
            'dislikes' => 'Dislikes',
            'interactions' => 'Likes & Dislikes',
            'comments' => 'Comments'
        ];
    }
}
