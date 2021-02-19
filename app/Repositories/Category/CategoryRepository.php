<?php

namespace App\Repositories\Category;

use App\Exceptions\FailedOperationException;
use App\Models\Category;
use App\Repositories\BaseRepository\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * The repository for the category model
 * PHP version >= 7.0
 *
 * @category Repositories
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * CategoryRepository constructor.
     *
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all the categories which are active
     *
     * @return Category[]|Collection
     */
    public function getAllActiveCategories(): Collection|array
    {
        return $this->model->where("status", $this->model::STATUS["active"])->get();
    }

    /**
     * @param array $properties
     *
     * @return Category|Model
     */
    public function create(array $properties): Model|Category
    {
        $properties = $this->addSlug($properties);
        return $this->model->create($properties);
    }

    /**
     * @param int   $id
     * @param array $properties
     *
     * @return Category|Category[]|Collection|Model|null
     * @throws FailedOperationException
     */
    public function updateById(int $id, array $properties): Model|Collection|array|Category|null
    {
        $properties = $this->addSlug($properties);
        $model = $this->model->findOrFail($id);
        if ($model->update($properties)) {
            return $model->fill($properties);
        }
        throw new FailedOperationException("Failed to update the category with id of $id");
    }

    /**
     * @param array $properties
     *
     * @return array
     */
    private function addSlug(array $properties): array
    {
        if (isset($properties["name"])) {
            $properties["slug"] = Str::slug($properties["name"]);
        }
        return $properties;
    }
}
