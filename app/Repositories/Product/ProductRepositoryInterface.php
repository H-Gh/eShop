<?php

namespace App\Repositories\Product;

use App\Exceptions\FailedOperationException;
use App\Models\Product;
use App\Repositories\BaseRepository\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * The repository interface for the product model
 * PHP version >= 7.0
 *
 * @product  Repositories
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all the categories which are active
     *
     * @return Product[]|Collection
     */
    public function getAllActiveProducts(): Collection|array;

    /**
     * @param array $properties
     *
     * @return Product|Model
     */
    public function create(array $properties): Model|Product;

    /**
     * @param int   $id
     * @param array $properties
     *
     * @return Product|Product[]|Collection|Model|null
     * @throws FailedOperationException
     */
    public function updateById(int $id, array $properties): Model|Collection|array|Product|null;
}
