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
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        $model = $this->get('type');

        return [
            'reason' => [
                'required',
                Rule::enum(ReportReason::class)
            ],
            'comment' => [
                'nullable',
                'string',
                'max:'.config('validation.report.comment.max')
            ],
            'type' => [
                'bail',
                'required',
                Rule::in([Video::class, Comment::class, User::class])
            ],
            'id' => [
                'required',
                'numeric',
                'exists:'.$model.',id',
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reason.required' => 'Please provide a reason for your review',
        ];
    }
}
