<?php

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;


class UpdateDeviceRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('personal_access_tokens', 'name')
                    ->where(fn (Builder $query) => $query->where('tokenable_id', Auth::user()->id))
                    ->ignore($this->route('device'))
            ]
        ];
    }
}
