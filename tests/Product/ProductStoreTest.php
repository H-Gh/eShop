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
class ProductStoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function testWrongMethod(): void
    {
        $product = Product::factory()->make()->toArray();
        $this->json("DELETE", $this->getRoute(), $product, $this->getRequestHeader())->seeStatusCode(405);
    }

    /**
     * @return void
     */
    public function testMissingEverything(): void
    {
        $this->json($this->getMethod(), $this->getRoute(), [], $this->getRequestHeader())->seeStatusCode(401);
    }

    /**
     * @return void
     */
    public function testUnauthorized(): void
    {
        $product = Product::factory()->make()->toArray();
        $this->json($this->getMethod(), $this->getRoute(), $product, $this->getRequestHeader())->seeStatusCode(401);
    }

    /**
     * @return void
     */
    public function testMissingName(): void
    {
        $data = $this->getRequestData(missingParameters: "name");
        $this->json($this->getMethod(), $this->getRoute(), $data, $this->getRequestHeader())
            ->seeStatusCode(422)
            ->seeJson(["name" => ["The name field is required."]]);
    }

    /**
     * @return void
     */
    public function testDuplicateNameSameCategory(): void
    {
        $productOne = Product::factory()->create();
        $productTwo = $this->getRequestData([],
            ["name" => $productOne["name"], "category_id" => $productOne["category_id"]]);
        $this->json($this->getMethod(), $this->getRoute(), $productTwo, $this->getRequestHeader())
            ->seeStatusCode(422)
            ->seeJson(["name" => ["The name has already been taken."]]);
    }

    /**
     * @return void
     */
    public function testDuplicateNameDifferentCategory(): void
    {
        $productOne = Product::factory()->create();
        $productTwo = $this->getRequestData([], ["name" => $productOne["name"]]);
        $request = $this->json($this->getMethod(), $this->getRoute(), $productTwo, $this->getRequestHeader())
            ->seeStatusCode(200);
        $response = $request->response->json();
        $this->seeInDatabase("products", [
            "name" => $response["name"],
            "price" => $response["price"],
            "category_id" => $response["category"]["id"],
            "status" => $response["status"],
        ]);
    }

    /**
     * @return void
     */
    public function testMissingCategoryId(): void
    {
        $data = $this->getRequestData(missingParameters: "category_id");
        $this->json($this->getMethod(), $this->getRoute(), $data, $this->getRequestHeader())
            ->seeStatusCode(422)
            ->seeJson(["category_id" => ["The category id field is required."]]);
    }

    /**
     * @return void
     */
    public function testMissingPrice(): void
    {
        $data = $this->getRequestData(missingParameters: "price");
        $this->json($this->getMethod(), $this->getRoute(), $data, $this->getRequestHeader())
            ->seeStatusCode(422)
            ->seeJson(["price" => ["The price field is required."]]);
    }

    /**
     * @return void
     */
    public function testWrongPrice(): void
    {
        $data = $this->getRequestData(missingParameters: ["price"]);
        $data["price"] = "test price";
        $this->json("POST", $this->getRoute(), $data, $this->getRequestHeader())
            ->seeStatusCode(422)
            ->seeJson(["price" => ["The price must be a number."]]);
    }

    /**
     * @return void
     */
    public function testMissingStatus(): void
    {
        $data = $this->getRequestData(missingParameters: "status");
        $request = $this->json($this->getMethod(), $this->getRoute(), $data, $this->getRequestHeader());
        $response = $request->response->json();
        $request->seeStatusCode(200);
        $this->assertContains($response["status"], Product::STATUS);
        $this->seeInDatabase("products", [
            "name" => $response["name"],
            "price" => $response["price"],
            "category_id" => $response["category"]["id"],
            "status" => $response["status"],
        ]);
    }

    /**
     * @return void
     */
    public function testWrongStatus(): void
    {
        $data = $this->getRequestData(missingParameters: ["status"]);
        $data["status"] = 10;
        $this->json($this->getMethod(), $this->getRoute(), $data, $this->getRequestHeader())
            ->seeStatusCode(422)
            ->seeJson(["status" => ["The selected status is invalid."]]);
    }

    /**
     * @return void
     */
    public function testResponse(): void
    {
        $data = $this->getRequestData(missingParameters: ["status"]);
        $request = $this->json($this->getMethod(), $this->getRoute(), $data, $this->getRequestHeader())
            ->seeStatusCode(200)
            ->seeJsonStructure([
                "id",
                "category" => ["id", "name", "status", "created_at", "updated_at"],
                "name",
                "price",
                "status",
                "created_at",
                "updated_at"
            ])
            ->seeJsonContains([
                "name" => $data["name"],
                "price" => $data["price"]
            ]);
        $response = $request->response->json();
        $this->assertEquals($response["category"]["id"], $data["category_id"]);
        $this->assertContains($response["status"], Product::STATUS);
    }

    /**
     * @param string|array $missingParameters
     * @param array        $attributes
     *
     * @return array
     */
    private function getRequestData(string|array $missingParameters = [], $attributes = []): array
    {
        if (isset($missingParameters["category_id"])) {
            $attributes["category_id"] = null;
        }
        $product = Product::factory()->make($attributes)->toArray();
        if (is_string($missingParameters)) {
            $missingParameters = [$missingParameters];
        }
        foreach ($missingParameters as $missingParameter) {
            if (isset($product[$missingParameter])) {
                unset($product[$missingParameter]);
            }
        }
        $user = User::factory()->create();
        $data = $product;
        $data["api_token"] = $user->api_token;
        return $data;
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
        return route("store_product");
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        return "POST";
    }
}
