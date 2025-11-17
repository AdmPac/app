<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductPatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'img' => 'sometimes|string',
            'name' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|nullable|string',
            'cost' => 'sometimes|nullable|numeric',
            'type_id' => 'sometimes|nullable|integer',
            'status_id' => 'sometimes|nullable|integer',
            'limit' => 'sometimes|nullable|integer',
        ];
    }
}
