<?php

namespace App\Http\Requests\Report;

use App\Enums\ReportReason;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
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
     * @return array
     */
    public function rules() : array
    {
        return [
            'reason' => [
                'required',
                Rule::in(ReportReason::valid())
            ],
            'comment' => [
                'nullable',
                'string',
                'max:'.config('validation.reason.comment.max')
            ],
            'id' => [
                'required',
                'numeric'
            ],
            'type' => [
                'required',
                Rule::in([Video::class, Comment::class, User::class])
            ],
        ];
    }
}
