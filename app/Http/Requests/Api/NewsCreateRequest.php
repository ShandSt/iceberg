<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class NewsCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
                'text',
            ],
            'text' => [
                'required',
                'text'
            ],
            'product_id' => [
                'required',
                'integer',
                'exists:products_new,id'
            ],
            'type' => [
                'required',
                'in:main,special'
            ],
            'image' => [
                'required',
                'iamage'
            ],
        ];
    }
}
