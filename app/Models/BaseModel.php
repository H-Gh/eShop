<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * The base model, the higher level model that includes all common functionalities
 * PHP version >= 7.0
 *
 * @category Models
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 * @mixin Builder
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class BaseModel extends Model
{
}
