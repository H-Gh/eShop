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
 * @property string $name
 * @property string $slug
 * @property int    $status
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
        "slug",
        "status"
    ];

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
