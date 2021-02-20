<?php

namespace App\Repositories\BaseRepository;

use App\Http\Filters\Filterable;
use App\Http\Filters\QueryFilters;
use App\Models\BaseModel;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
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
     * @var Builder
     */
    protected Builder $query;

    /**
     * BaseRepository constructor.
     *
     * @param BaseModel $model
     */
    public function __construct(protected BaseModel $model) {
        $this->query = $this->model->query();
    }

    /**
     * @param QueryFilters $filter
     *
     * @return ProductRepository
     */
    public function setFilter(QueryFilters $filter): BaseRepositoryInterface
    {
        if ($this->isModelFilterable()) {
            $this->query->filter($filter);
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
        return $this->query->get();
    }

    /**
     * @param int|null $itemPerPage
     *
     * @return LengthAwarePaginator
     */
    public function paginate(?int $itemPerPage): LengthAwarePaginator
    {
        return $this->query->paginate($itemPerPage);
    }

    /**
     * @param $id
     *
     * @return BaseModel|BaseModel[]|Collection|Model|null
     */
    public function findByIdOrFail($id): Model|Collection|array|BaseModel|null
    {
        return $this->query->findOrFail((int)$id);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function deleteById(int $id): bool
    {
        return $this->query->findOrFail($id)->delete();
    }
}
