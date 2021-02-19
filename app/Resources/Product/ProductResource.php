<?php

namespace App\Resources\Product;

use App\Models\Product;
use App\Resources\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The resource for the product model
 * PHP version >= 7.0
 *
 * @category Resources
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "category" => new CategoryResource($this->category),
            "name" => $this->name,
            "status" => $this->status
        ];
    }
}
