<?php

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * The test for creating product model endpoint
 * PHP version >= 7.0
 *
 * @category Tests
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class ProductUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function testWrongMethod(): void
    {
        $product = Product::factory()->make()->toArray();
        $this->json("POST", $this->getRoute(1000), $product, $this->getRequestHeader())->seeStatusCode(405);
    }

    /**
     * @return void
     */
    public function testMissingEverything(): void
    {
        $this->json($this->getMethod(), $this->getRoute(1000), [], $this->getRequestHeader())->seeStatusCode(401);
    }

    /**
     * @return void
     */
    public function testUnauthorized(): void
    {
        $product = Product::factory()->make()->toArray();
        $this->json($this->getMethod(), $this->getRoute(1000), $product, $this->getRequestHeader())->seeStatusCode(401);
    }

    /**
     * @return void
     */
    public function testMissingData(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $data = $this->getRequestData($product, ["name", "price", "category_id", "status"]);
        $request = $this->json($this->getMethod(), $this->getRoute($product->id), $data, $this->getRequestHeader())
            ->seeStatusCode(200)
            ->seeJsonContains([
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
            ]);
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
    public function testWrongData(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $data = $this->getRequestData($product);
        $data["price"] = "Wrong price";
        $data["status"] = 1000;
        $this->json($this->getMethod(), $this->getRoute($product->id), $data, $this->getRequestHeader())
            ->seeStatusCode(422)
            ->seeJsonEquals([
                "price" => ["The price must be a number."],
                "status" => ["The selected status is invalid."]
            ]);
    }

    /**
     * @return void
     */
    public function testUpdateData(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $data = $this->getRequestData($product);
        $data["price"] = 2001;
        $data["name"] = "Updated name";
        $data["id"] = "Test Id";
        $data["status"] = 2;
        $data["created_at"] = Carbon::now()->format("Y/m/d H:i:s");
        $data["updated_at"] = Carbon::now()->format("Y/m/d H:i:s");
        $this->json($this->getMethod(), $this->getRoute($product->id), $data, $this->getRequestHeader())
            ->seeStatusCode(200)
            ->seeJsonContains([
                "id" => $product->id,
                "name" => $data["name"],
                "price" => $data["price"],
                "category" => [
                    "id" => $product->category->id,
                    "name" => $product->category->name,
                    "status" => $product->category->status,
                    "created_at" => $product->category->created_at->format("Y/m/d H:i:s"),
                    "updated_at" => $product->category->updated_at->format("Y/m/d H:i:s"),
                ],
                "status" => $data["status"],
                "created_at" => $product->created_at->format("Y/m/d H:i:s"),
            ]);
        $this->seeInDatabase("products", [
            "id" => $product->id,
            "name" => $data["name"],
            "price" => $data["price"],
            "category_id" => $data["category_id"],
            "status" => $data["status"],
        ]);
    }

    /**
     * @param Product      $product
     * @param string|array $missingParameters
     *
     * @return array
     */
    private function getRequestData(Product $product, string|array $missingParameters = []): array
    {
        $product = $product->toArray();
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
     * @param int $id
     *
     * @return string
     */
    private function getRoute(int $id): string
    {
        return route("update_product", ["id" => $id]);
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        return "PUT";
    }
}
