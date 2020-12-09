<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'exists:users',
            ],
            /*
            'os' => [
                'required',
                'in:android,ios',
            ],
            'token' => [
                'required'
            ],
            'device_id' => [
                'required',
            ],
            'allow_push' => [
                'required',
                'integer',
                'between:0,1',
            ],*/
        ];
    }
}
