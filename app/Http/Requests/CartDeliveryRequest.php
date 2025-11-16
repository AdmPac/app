<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|regex:/^\\+?[0-9-]{9,}$/',
            'address' => 'required|min:1',
        ];
    }
}


