<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && ! Auth::user()->has_debt;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'address_id' => 'required|integer|exists:address,id',
            'bottles' => 'required|integer',
            'products' => 'required',
            'products.*.id' => 'required|integer|exists:products_new,id',
            'products.*.count' => 'required|integer|min:0',
            'payment_method' => 'in:Card,Cash,Contract',
        ];
    }
}
