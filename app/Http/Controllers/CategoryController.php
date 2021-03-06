<?php

namespace App\Http\Controllers;

use App\Exceptions\FailedOperationException;
use App\Http\Filters\CategoryListFilter;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Resources\Category\CategoryResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Response;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers
 */
class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * @param CategoryListFilter $filter
     *
     * @return AnonymousResourceCollection
     */
    public function index(CategoryListFilter $filter): AnonymousResourceCollection
    {
        $activeCategories = $this->categoryRepository->setFilter(filter: $filter);
        $itemPerPage = $this->itemPerPage();
        if (empty($this->itemPerPage())) {
            return CategoryResource::collection(resource: $activeCategories->all());
        } else {
            return CategoryResource::collection(resource: $activeCategories->paginate($itemPerPage));
        }

    }

    /**
     * @param StoreCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryRepository->create(properties: $request->toArray());
        return Response::json(new CategoryResource($category));
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $category = $this->categoryRepository->findByIdOrFail(id: $id);
        return Response::json(new CategoryResource($category));
    }

    /**
     * @param                       $id
     * @param UpdateCategoryRequest $request
     *
     * @return JsonResponse
     * @throws FailedOperationException
     */
    public function update($id, UpdateCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryRepository->updateById(id: (int)$id, properties: $request->toArray());
        return Response::json(new CategoryResource($category));
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id): JsonResponse
    {
        $response = ["success" => false];
        if ($this->categoryRepository->deleteById(id: (int)$id)) {
            $response["success"] = true;
        }
        return Response::json($response);
    }
}
