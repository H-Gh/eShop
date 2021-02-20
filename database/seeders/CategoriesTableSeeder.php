<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/**
 * Class CategoryTableSeeder
 *
 * @package Database\Seeders
 */
class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(10)->create()->each(function (Category $category) {
            $products = Product::factory()->count(20)->make([
                "category_id" => $category->id
            ]);
            $category->products()->saveMany($products);
        });
    }
}
