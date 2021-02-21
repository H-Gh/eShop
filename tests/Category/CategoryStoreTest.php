<?php

use App\Models\Category;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * The test for creating category model endpoint
 * PHP version >= 7.0
 *
 * @category Tests
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class CategoryStoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function testWrongMethod(): void
    {
        $category = Category::factory()->make()->toArray();
        $this->json("DELETE", $this->getRoute(), $category, $this->getRequestHeader())->seeStatusCode(405);
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
        $category = Category::factory()->make()->toArray();
        $this->json($this->getMethod(), $this->getRoute(), $category, $this->getRequestHeader())->seeStatusCode(401);
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
    public function testDuplicateName(): void
    {
        $categoryOne = Category::factory()->create();
        $categoryTwo = $this->getRequestData([], ["name" => $categoryOne["name"]]);
        $this->json($this->getMethod(), $this->getRoute(), $categoryTwo, $this->getRequestHeader())
            ->seeStatusCode(422)
            ->seeJson(["name" => ["The name has already been taken."]]);
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
        $this->assertContains($response["status"], Category::STATUS);
        $this->seeInDatabase("categories", [
            "name" => $response["name"],
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
                "name",
                "status",
                "created_at",
                "updated_at"
            ])
            ->seeJsonContains(["name" => $data["name"],]);
        $response = $request->response->json();
        $this->assertContains($response["status"], Category::STATUS);
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
        $category = Category::factory()->make($attributes)->toArray();
        if (is_string($missingParameters)) {
            $missingParameters = [$missingParameters];
        }
        foreach ($missingParameters as $missingParameter) {
            if (isset($category[$missingParameter])) {
                unset($category[$missingParameter]);
            }
        }
        $user = User::factory()->create();
        $data = $category;
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
        return route("store_category");
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        return "POST";
    }
}
