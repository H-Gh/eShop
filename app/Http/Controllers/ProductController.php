<?php

namespace App\Http\Controllers;

use App\Exceptions\FailedOperationException;
use App\Http\Filters\ProductListFilter;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Resources\Product\ProductResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

/**
 * Class ProductController
 *
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{

    /**
     * CategoryController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param ProductListFilter $filters
     *
     * @return JsonResponse
     */
    public function index(ProductListFilter $filters): JsonResponse
    {
        $activeCategories = $this->productRepository->setFilter($filters)->all();
        return Response::json(ProductResource::collection(resource: $activeCategories));
    }

    /**
     * @param StoreProductRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productRepository->create(properties: $request->toArray());
        return Response::json(new ProductResource($product));
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $product = $this->productRepository->findByIdOrFail(id: $id);
        return Response::json(new ProductResource($product));
    }

    /**
     * @param                       $id
     * @param UpdateProductRequest  $request
     *
     * @return JsonResponse
     * @throws FailedOperationException
     */
    public function update($id, UpdateProductRequest $request): JsonResponse
    {
        $product = $this->productRepository->updateById(id: (int)$id, properties: $request->toArray());
        return Response::json(new ProductResource($product));
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
        if ($this->productRepository->deleteById(id: (int)$id)) {
            $response["success"] = true;
        }
        return Response::json($response);
    }
}
