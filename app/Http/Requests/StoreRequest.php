<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => ['required', 'max:80'],
            'phone' => ['required', 'unique:stores,phone,' . $this->id, 'max:20', "regex:(^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$)"],
            'address' => ['required', 'max:100'],
            'zipcode' => ['required', 'digits:5'],
            'owner' => ['required', 'max:100', "regex:(^[a-zA-Z][a-zA-Z\sñÑ]{0,99}[a-zA-ZÑñ]$)"],
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
