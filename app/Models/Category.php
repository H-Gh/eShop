<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use Filterable;
    use HasFactory;

    public const STATUS = [
        "active" => 1,
        "inactive" => 2
    ];

    protected $fillable = [
        "name",
        "slug",
        "status"
    ];

    protected $attributes = [
        'status' => self::STATUS["active"],
    ];

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
