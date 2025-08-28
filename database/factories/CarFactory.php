<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    protected $model = \App\Models\Car::class;

    public function definition(): array
    {
        return [
            'brand_id' => Brand::factory(),
            'car_model_id' => CarModel::factory(),
            'user_id' => User::factory(),
            'year' => (int) $this->faker->numberBetween(1990, (int)date('Y')),
            'mileage' => $this->faker->numberBetween(0, 200000),
            'color' => $this->faker->safeColorName(),
        ];
    }
}
