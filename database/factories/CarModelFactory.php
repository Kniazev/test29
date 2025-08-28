<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarModelFactory extends Factory
{
    protected $model = \App\Models\CarModel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'brand_id' => Brand::factory(),
        ];
    }
}
