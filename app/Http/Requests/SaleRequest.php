<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{

    public function rules()
    {
        return [
            'quantity' => ['required'],
            'total' => ['required'],
        ];
    }
}
