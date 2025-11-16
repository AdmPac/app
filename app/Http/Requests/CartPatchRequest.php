<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CartPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = (int) $this->route('id');
        $product = Product::findOrFail($productId);
        $limit = (int) $product->limit;

        return [
            'quantity' => "required|integer|min:1|max:$limit",
        ];
    }
}


