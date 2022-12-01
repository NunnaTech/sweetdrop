<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{

    public function rules()
    {
        return [
            'sku' => ['required', 'max:30'],
            'name' => ['required', 'unique:products,name,' . $this->id, 'max:50', "regex:(^[a-zA-Z][a-zA-Z\sñÑáéíóúÁÉÍÓÚ]{0,99}[a-zA-ZÑñáéíóúÁÉÍÓÚ]$)"],
            'description' => ['max:255',"regex:(^[a-zA-Z][a-zA-Z\sñÑáéíóúÁÉÍÓÚ]{0,99}[a-zA-ZÑñáéíóúÁÉÍÓÚ]$)"],
            'price' => ['required', 'digits:10'],
            'image' => [ 'max:150'],
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
            'phone.regex' => 'The :attribute must be a correct :attribute',
            'owner.regex' => 'The :attribute must be a correct :attribute',
        ];
    }
}
