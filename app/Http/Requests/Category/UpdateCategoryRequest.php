<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\FormRequest;
use App\Models\Category;
use Illuminate\Validation\Rule;

/**
 * The request to check the incoming parameters to update a category
 * PHP version >= 7.0
 *
 * @category Requests
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class UpdateCategoryRequest extends FormRequest
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
                "string",
                Rule::unique(Category::class, "name")->where(function ($query) {
                    $query->where("id", "!=", request()->route()[2]["id"]);
                })
            ],
            "status" => "integer|in:" . implode(",", Category::STATUS)
        ];
    }
}
