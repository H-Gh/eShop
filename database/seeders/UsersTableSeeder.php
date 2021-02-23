<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Class UsersTableSeeder
 *
 * @package Database\Seeders
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(50)->create();
        User::firstOrCreate([
            "name" => "test user",
        ], [
            'email' => "test@test.com",
            'password' => app('hash')->make(Str::random()),
            'api_token' => 'GBj2b03qT0mzJeoxxexqFbImsPtSRq',
        ]);
    }
}
