<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'received_by' => ['required', 'max:100'],
            'store_id' => ['required', 'numeric'],
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'numeric'],
            'products.*.quantity' => ['required', 'numeric'],
            'comment' => ['required','string'],
            'images' => ['array'],
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
        ];
    }
}
