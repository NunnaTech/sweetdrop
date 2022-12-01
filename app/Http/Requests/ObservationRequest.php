<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class ObservationRequest extends FormRequest
{

    public function rules()
    {
        return [
            'comment' => ['required', 'max:100', "regex:(^[a-zA-Z][a-zA-Z\sñÑáéíóúÁÉÍÓÚ]{0,99}[a-zA-ZÑñáéíóúÁÉÍÓÚ]$)"],
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
