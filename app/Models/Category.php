<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The category model
 * PHP version >= 7.0
 *
 * @category Models
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class Category extends BaseModel
{
    public const STATUS = [
        "active" => 1,
        "inactive" => 2
    ];

    protected $fillable = [
        "id",
        "name",
        "slug"
    ];

    /**
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
