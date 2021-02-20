<?php

namespace App\Http\Filters;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class QueryFilters
 *
 * @category Filters
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class QueryFilters
{
    /**
     * QueryFilters constructor.
     *
     * @param Request $request The received request
     */
    public function __construct(protected Request $request)
    {
    }

    /**
     * This method will apply the received filters to query
     *
     * @param Builder $builder The query builder
     *
     * @return Builder
     * @throws InvalidParameterException
     */
    public function apply(Builder $builder): Builder
    {
        $filters = $this->getFilters();
        foreach ($filters as $name => $value) {
            $this->addFilterToQueryBuilder($builder, $name, $value);
        }
        return $builder;
    }

    /**
     * This method return all requests as filters to delete
     *
     * @return array
     */
    private function getFilters(): array
    {
        $filters = [];
        foreach ($this->request->all() as $name => $value) {
            $name = Str::camel($name);
            if (!method_exists($this, $name)) {
                continue;
            }
            $filters[$name] = $value;
        }
        return $filters;
    }

    /**
     * This method will add the filter to Query Builder
     *
     * @param Builder $builder
     * @param string  $name  The name of filter
     * @param mixed   $value The value of filter
     *
     * @return void
     * @throws InvalidParameterException
     */
    private function addFilterToQueryBuilder(Builder $builder, string $name, $value): void
    {
        if ($value != 0 && empty($value)) {
            throw new InvalidParameterException(Str::snake($name));
        }
        $this->$name($builder, $value);
    }
}
