<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * The filter which applies to category's list
 * PHP version >= 7.0
 *
 * @category Filters
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class CategoryListFilter extends QueryFilters
{
    /**
     * @param Builder $builder
     * @param int     $status
     *
     * @return void
     */
    public function status(Builder $builder, int $status): void
    {
        $builder->where("status", $status);
    }

    /**
     * @param Builder $builder
     * @param string  $name
     *
     * @return void
     */
    public function name(Builder $builder, string $name): void
    {
        $builder->where("name", $name);
    }
}
