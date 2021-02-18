<?php

namespace App\Repositories\Category;

use App\Exceptions\FailedOperationException;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * The repository interface for the category model
 * PHP version >= 7.0
 *
 * @category Repositories
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
interface CategoryRepositoryInterface
{
    /**
     * Get all the categories which are active
     *
     * @return Category[]|Collection
     */
    public function getAllActiveCategories(): Collection|array;

    /**
     * @param array $properties
     *
     * @return Category|Model
     */
    public function create(array $properties): Model|Category;

    /**
     * @param $id
     *
     * @return Category|Category[]|Collection|Model|null
     */
    public function findByIdOrFail($id): Model|Collection|array|Category|null;

    /**
     * @param int   $id
     * @param array $properties
     *
     * @return Category|Category[]|Collection|Model|null
     * @throws FailedOperationException
     */
    public function updateById(int $id, array $properties): Model|Collection|array|Category|null;

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function deleteById(int $id): bool;
}
