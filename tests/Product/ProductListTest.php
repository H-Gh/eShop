<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * The test for creating product model endpoint
 * PHP version >= 7.0
 *
 * @category Tests
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class ProductListTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function testWrongMethod(): void
    {
        $this->json("DELETE", $this->getRoute(), [], $this->getRequestHeader())->seeStatusCode(405);
    }

    /**
     * @return void
     */
    public function testResponse(): void
    {
        $this->json($this->getMethod(), $this->getRoute(), [], $this->getRequestHeader())
            ->seeStatusCode(200)
            ->seeJsonStructure([
                "data" => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'category' => ["id", "name", "status", "created_at", "updated_at"],
                        'created_at',
                        'updated_at'
                    ]
                ]
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
     * @return string
     */
    private function getRoute(): string
    {
        return route("list_products");
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        return "GET";
    }
}
