<?php

namespace App\Repositories\BaseRepository;

use App\Http\Filters\QueryFilters;
use App\Models\BaseModel;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * The base repository interface
 * PHP version >= 7.0
 *
 * @category Repositories
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
interface BaseRepositoryInterface
{
    /**
     * @param QueryFilters $filter
     *
     * @return BaseRepositoryInterface
     */
    public function setFilter(QueryFilters $filter): BaseRepositoryInterface;

    /**
     * @return Product[]|Collection
     */
    public function all(): Collection|array;

    /**
     * @param $id
     *
     * @return BaseModel|BaseModel[]|Collection|Model|null
     */
    public function findByIdOrFail($id): Model|Collection|array|BaseModel|null;

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function deleteById(int $id): bool;
}
