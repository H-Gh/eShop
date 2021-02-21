<?php

use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * The test for creating category model endpoint
 * PHP version >= 7.0
 *
 * @category Tests
 * @package  eShop
 * @author   Hamed Ghasempour <hamedghasempour@gmail.com>
 */
class CategoryUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function testWrongMethod(): void
    {
        $category = Category::factory()->make()->toArray();
        $this->json("POST", $this->getRoute(1000), $category, $this->getRequestHeader())->seeStatusCode(405);
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
        $category = Category::factory()->make()->toArray();
        $this->json($this->getMethod(), $this->getRoute(1000), $category, $this->getRequestHeader())
            ->seeStatusCode(401);
    }

    /**
     * @return void
     */
    public function testMissingData(): void
    {
        /** @var Category $category */
        $category = Category::factory()->create();
        $data = $this->getRequestData($category, ["name", "status"]);
        $request = $this->json($this->getMethod(), $this->getRoute($category->id), $data, $this->getRequestHeader())
            ->seeStatusCode(200)
            ->seeJsonContains([
                "id" => $category->id,
                "name" => $category->name,
                "status" => $category->status,
                "created_at" => $category->created_at->format("Y/m/d H:i:s"),
            ]);
        $response = $request->response->json();
        $this->seeInDatabase("categories", [
            "name" => $response["name"],
            "status" => $response["status"],
        ]);
    }

    /**
     * @return void
     */
    public function testWrongData(): void
    {
        /** @var Category $category */
        $category = Category::factory()->create();
        $data = $this->getRequestData($category);
        $data["status"] = 1000;
        $this->json($this->getMethod(), $this->getRoute($category->id), $data, $this->getRequestHeader())
            ->seeStatusCode(422)
            ->seeJsonEquals(["status" => ["The selected status is invalid."]]);
    }

    /**
     * @return void
     */
    public function testUpdateData(): void
    {
        /** @var Category $category */
        $category = Category::factory()->create();
        $data = $this->getRequestData($category);
        $data["name"] = "Updated name";
        $data["id"] = "Test Id";
        $data["status"] = 2;
        $data["created_at"] = Carbon::now()->format("Y/m/d H:i:s");
        $data["updated_at"] = Carbon::now()->format("Y/m/d H:i:s");
        $this->json($this->getMethod(), $this->getRoute($category->id), $data, $this->getRequestHeader())
            ->seeStatusCode(200)
            ->seeJsonContains([
                "id" => $category->id,
                "name" => $data["name"],
                "status" => $data["status"],
                "created_at" => $category->created_at->format("Y/m/d H:i:s"),
            ]);
        $this->seeInDatabase("categories", [
            "id" => $category->id,
            "name" => $data["name"],
            "status" => $data["status"],
        ]);
    }

    /**
     * @param Category     $category
     * @param string|array $missingParameters
     *
     * @return array
     */
    private function getRequestData(Category $category, string|array $missingParameters = []): array
    {
        $category = $category->toArray();
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
     * @param int $id
     *
     * @return string
     */
    private function getRoute(int $id): string
    {
        return route("update_category", ["id" => $id]);
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        return "PUT";
    }
}
