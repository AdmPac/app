<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order\Status;
use Illuminate\Validation\Rule;

class StatusPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statuses = Status::all()->toArray();

        return [
            'status' => [
                'required',
                'integer',
                Rule::in(array_column($statuses, 'id')),
            ],
        ];
    }
}


