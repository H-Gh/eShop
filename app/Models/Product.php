<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The product model
 * PHP version >= 7.0
 *
 * @category Models
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class Product extends BaseModel
{
    public const STATUS = [
        "active" => 1,
        "inactive" => 2,
        "suspended" => 3,
        "to-review" => 4,
    ];

    protected $fillable = [
        "id",
        "name",
        "slug",
        "category_id"
    ];

    /**
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
