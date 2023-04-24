<?php

namespace App\Http\Requests\Report;

use App\Enums\ReportReason;
use App\Enums\VideoStatus;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Closure;

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
                Rule::in(ReportReason::valid())
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
                function (string $attribute, mixed $value, Closure $fail) use ($model) {
                   $exist = (new $model)
                       ->where($attribute, $value)
                       ->when(in_array($this->type, [Video::class, Comment::class]), fn($q) => $q->where('user_id', '!=', Auth::user()->id))
                       ->when($this->type == User::class, fn($q) => $q->where('id', '!=', Auth::user()->id)->whereNull('banned_at'))
                       ->when($this->type == Comment::class, fn($q) => $q->whereNull('banned_at'))
                       ->when($this->type == Video::class, fn($q) => $q->where(function ($query) {
                           $query->whereIn('status', [VideoStatus::PUBLIC, VideoStatus::UNLISTED])
                               ->orWhere(function($query) {
                                   $query->where('status', VideoStatus::PLANNED)
                                       ->where('scheduled_date', '<=', now());
                               });
                        }))->whereDoesntHave('reports', fn($query) => $query->where([
                           'reportable_type' => $model,
                           'reportable_id' => $value,
                           'user_id' => Auth::user()->id
                       ]))
                       ->exists();

                   if (!$exist) {
                       $fail("{$attribute} is invalid.");
                   }
                }
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
