<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewsRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
            ],
            'text' => [
                'required',
                'string',
            ],
            'product_id' => [
                'integer',
                'exists:products_new,id',
            ],
            'type' => [
                'required',
                'in:main,special',
            ],
            'image' => [
                'required',
                'string'
            ],
        ];
    }
}
