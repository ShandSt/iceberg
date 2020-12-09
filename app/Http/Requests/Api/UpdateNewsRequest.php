<?php

namespace App\Http\Requests\Api;

use App\Models\News;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  News::where('id', $this->route('news'))->exists();
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
                'string',
            ],
            'text' => [
                'string',
            ],
            'product_id' => [
                'integer',
                'exists:products_new,id',
            ],
            'type' => [
                'in:mail,special',
            ],
            'image' => [
                'string',
            ],
        ];
    }
}
