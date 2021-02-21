<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * The test for creating product model endpoint
 * PHP version >= 7.0
 *
 * @category Tests
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class ProductDestroyTest extends TestCase
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
    public function testUnauthorized(): void
    {
        $product = Product::factory()->create();
        $this->json($this->getMethod(), $this->getRoute($product->id), [], $this->getRequestHeader())
            ->seeStatusCode(401);
    }

    /**
     * @return void
     */
    public function testResponse(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $this->json($this->getMethod(), $this->getRoute($product->id), ["api_token" => $user->api_token],
            $this->getRequestHeader())->seeStatusCode(200)->seeJsonEquals(["success" => true]);
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
        return route("destroy_product", ["id" => $id]);
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        return "DELETE";
    }
}
