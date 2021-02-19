<?php

namespace App\Http\Filters;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Filterable
 *
 * @category Filters
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 * @method Builder filter(QueryFilters $filters);
 */
trait Filterable
{
    /**
     * This method will call when ::filter method calls through a query
     *
     * @param Builder      $builder The query builder
     * @param QueryFilters $filters The filters
     *
     * @return Builder
     * @throws InvalidParameterException
     */
    public function scopeFilter(Builder $builder, QueryFilters $filters): Builder
    {
        return $filters->apply($builder);
    }
}
