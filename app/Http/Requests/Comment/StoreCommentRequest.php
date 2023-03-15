<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        return [
            'video_id' => 'required|exists:videos,id',
            'parent_id' => [
                'nullable',
                Rule::exists('comments', 'id')->where(function ($query) {
                    return $query->whereNull('parent_id');
                }),
            ],
            'content' => [
                'required',
                'string',
                'max:'.config('validation.comment.content.max')
            ]
        ];
    }
}
