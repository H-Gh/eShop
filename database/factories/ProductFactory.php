<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ProductFactory
 *
 * @package Database\Factories
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'slug' => $this->faker->unique()->safeEmail,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'category_id' => function () {
                return Category::factory()->create()->id;
            },
        ];
    }
}
