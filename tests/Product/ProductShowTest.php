<?php

use App\Models\Product;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * The test for creating product model endpoint
 * PHP version >= 7.0
 *
 * @category Tests
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class ProductShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function testWrongMethod(): void
    {
        $this->json("POST", $this->getRoute(1000), [], $this->getRequestHeader())->seeStatusCode(405);
    }

    /**
     * @return void
     */
    public function testResponse(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $this->json($this->getMethod(), $this->getRoute($product->id), [], $this->getRequestHeader())
            ->seeStatusCode(200)
            ->seeJsonEquals([
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "category" => [
                    "id" => $product->category->id,
                    "name" => $product->category->name,
                    "status" => $product->category->status,
                    "created_at" => $product->category->created_at->format("Y/m/d H:i:s"),
                    "updated_at" => $product->category->updated_at->format("Y/m/d H:i:s"),
                ],
                "status" => $product->status,
                "created_at" => $product->created_at->format("Y/m/d H:i:s"),
                "updated_at" => $product->updated_at->format("Y/m/d H:i:s")
            ]);
    }

    /**
     * @return string[]
     */
    private function getRequestHeader(): array
    {
        return ["accept" => "application/json"];
    }

    /**
     * @param int $id
     *
     * @return string
     */
    private function getRoute(int $id): string
    {
        return route("show_product", ["id" => $id]);
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        return "GET";
    }
}
