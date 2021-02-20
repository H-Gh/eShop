<?php

namespace App\Repositories\Product;

use App\Exceptions\FailedOperationException;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\BaseRepository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * The repository for the product model
 * PHP version >= 7.0
 *
 * @product  Repositories
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * CategoryRepository constructor.
     *
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all the products which are active
     *
     * @return Product[]|Collection
     */
    public function getAllActiveProducts(): Collection|array
    {
        return $this->query->where("status", $this->query::STATUS["active"])->get();
    }

    /**
     * @param array $properties
     *
     * @return Product|Model
     */
    public function create(array $properties): Model|Product
    {
        $properties = $this->addSlug($properties);
        return $this->query->create($properties);
    }

    /**
     * @param int   $id
     * @param array $properties
     *
     * @return Product|Product[]|Collection|Model|null
     * @throws FailedOperationException
     */
    public function updateById(int $id, array $properties): Model|Collection|array|Product|null
    {
        $properties = $this->addSlug($properties);
        $model = $this->query->findOrFail($id);
        if ($model->update($properties)) {
            return $model->fill($properties);
        }
        throw new FailedOperationException("Failed to update the product with id of $id");
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
