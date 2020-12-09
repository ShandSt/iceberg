<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserAdressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'street' => 'required|string',
            'house' => 'required|string',
            'entrance' => 'integer|nullable',
            'floor' => 'integer|nullable',
            'apartment' => 'nullable',
            'comment' => 'string|nullable',
            'city_id' => 'exists:cities,id',
        ];
    }
}
