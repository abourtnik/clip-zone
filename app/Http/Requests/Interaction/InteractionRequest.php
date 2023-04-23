<?php

namespace App\Http\Requests\Interaction;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InteractionRequest extends FormRequest
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
        $model = $this->get('model');

        return [
            'model' => [
                'bail',
                'required',
                Rule::in([Video::class, Comment::class])
            ],
            'id' => [
                'required',
                'numeric',
                Rule::exists((new $model())->getTable(), 'id')->where(function ($query) use ($model) {
                    return (new $model)->scopePublic($query)->orWhere('user_id', Auth::user()->id);
                })
            ],
        ];
    }
}
