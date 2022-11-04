<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
{
    public function rules()
    {
        // Uniques will be: unique:users,email,id
        // unique:<table>,<column_table>,<id> WHEN WE JUST UPDATE
        return [
            'email' => ['required', 'email', 'unique:users,email,' . $this->id, 'max:50'],
            'password' => ['required', 'max:50'],
            'name' => ['required', 'max:50', "regex:(^[a-zA-Z][a-zA-Z\sñÑ]{0,49}[a-zA-ZÑñ]$)"],
            'first_surname' => ['required', 'max:50', "regex:(^[a-zA-Z][a-zA-Z\sñÑ]{0,49}[a-zA-ZÑñ]$)"],
            'second_surname' => ['max:50', "regex:(^[a-zA-Z][a-zA-Z\sñÑ]{0,49}[a-zA-ZÑñ]$)"],
            'phone' => ['required', 'unique:users,phone,' . $this->id, 'max:20', "regex:(^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$)"],
            'role_id' => ['required', "numeric"],
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
            'name.regex' => 'The :attribute must be a correct :attribute',
            'first_surname.regex' => 'The :attribute must be a correct :attribute',
            'phone.regex' => 'The :attribute must be a correct :attribute',
        ];
    }
}
