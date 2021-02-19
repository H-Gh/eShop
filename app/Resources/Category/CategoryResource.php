<?php

namespace App\Resources\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The resource for the category model
 * PHP version >= 7.0
 *
 * @category Resources
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "status" => $this->status
        ];
    }
}
