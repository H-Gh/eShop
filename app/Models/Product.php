<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The product model
 * PHP version >= 7.0
 *
 * @category Models
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 * @property int      $id
 * @property string   $name
 * @property string   $slug
 * @property string   $category_id
 * @property int      $status
 * @property Category $category
 */
class Product extends BaseModel
{
    use Filterable;
    use HasFactory;

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
        "category_id",
        "status"
    ];

    protected $attributes = [
        'status' => self::STATUS["active"],
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
