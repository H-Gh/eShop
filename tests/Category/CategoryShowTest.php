<?php

use App\Models\Category;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * The test for creating category model endpoint
 * PHP version >= 7.0
 *
 * @category Tests
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class CategoryShowTest extends TestCase
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
        /** @var Category $category */
        $category = Category::factory()->create();
        $this->json($this->getMethod(), $this->getRoute($category->id), [], $this->getRequestHeader())
            ->seeStatusCode(200)
            ->seeJsonEquals([
                "id" => $category->id,
                "name" => $category->name,
                "status" => $category->status,
                "created_at" => $category->created_at->format("Y/m/d H:i:s"),
                "updated_at" => $category->updated_at->format("Y/m/d H:i:s")
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
        return route("show_category", ["id" => $id]);
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        return "GET";
    }
}
