<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем 10 случайных машин
        Car::factory(10)->create();

        // Создаем одну конкретную машину для тестов
        Car::factory()->create([
            'brand_id' => 1, // Toyota
            'car_model_id' => 1, // Camry
            'year' => 2021,
            'mileage' => 30000,
            'color' => 'Silver',
        ]);
    }
}
