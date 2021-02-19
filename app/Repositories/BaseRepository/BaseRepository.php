<?php

namespace App\Repositories\BaseRepository;

use App\Http\Filters\Filterable;
use App\Http\Filters\QueryFilters;
use App\Models\BaseModel;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * The base repository to hold all common methods between repositories
 * PHP version >= 7.0
 *
 * @category Repositories
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class BaseRepository implements BaseRepositoryInterface
{
    /**
     * BaseRepository constructor.
     *
     * @param BaseModel $model
     */
    public function __construct(protected BaseModel $model) { }

    /**
     * @param QueryFilters $filters
     *
     * @return ProductRepository
     */
    public function setFilters(QueryFilters $filters): BaseRepositoryInterface
    {
        if ($this->isModelFilterable()) {
            $this->model->filter($filters);
        }
        return $this;
    }

    /**
     * @return bool
     */
    private function isModelFilterable(): bool
    {
        return in_array(Filterable::class, class_uses($this->model));
    }

    /**
     * @return Product[]|Collection
     */
    public function all(): Collection|array
    {
        return $this->model->get();
    }

    /**
     * @param $id
     *
     * @return BaseModel|BaseModel[]|Collection|Model|null
     */
    public function findByIdOrFail($id): Model|Collection|array|BaseModel|null
    {
        return $this->model->findOrFail((int)$id);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function deleteById(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
