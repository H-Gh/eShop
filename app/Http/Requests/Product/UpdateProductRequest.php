<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\FormRequest;
use App\Models\Product;
use Illuminate\Validation\Rule;

/**
 * The request to check the incoming parameters to update a product
 * PHP version >= 7.0
 *
 * @category Requests
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => [
                "required",
                "string",
                Rule::unique(Product::class, "name")
                    ->where("category_id", request()->get("category_id"))
                    ->where(function ($query) {
                        $query->where("id", "!=", request()->route()[2]["id"]);
                    })
            ],
            "category_id" => "required|integer|exists:categories,id",
            "status" => "integer|in:" . implode(",", Product::STATUS)
        ];
    }
}
