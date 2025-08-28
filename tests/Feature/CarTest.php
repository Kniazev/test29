<?php

namespace Tests\Feature;

use App\Jobs\CarAssignedJob;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_car(): void
    {
        $user = User::factory()->create();
        $brand = Brand::factory()->create();
        $model = CarModel::factory()->create(['brand_id' => $brand->id]);

        $carData = [
            'brand_id' => $brand->id,
            'car_model_id' => $model->id,
            'year' => 2022,
            'mileage' => 5000,
            'color' => 'blue',
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/cars', $carData);

        $response->assertStatus(201)
            ->assertJsonFragment($carData);
        $this->assertDatabaseHas('cars', $carData);
    }

    public function test_user_can_see_only_their_cars(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $car1 = Car::factory()->create(['user_id' => $user1->id]);
        $car2 = Car::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1, 'sanctum')->getJson('/api/cars');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $car1->id])
            ->assertJsonMissing(['id' => $car2->id]);
    }

    public function test_user_can_update_their_car(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $updateData = ['color' => 'black', 'mileage' => 20000];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/cars/{$car->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);
        $this->assertDatabaseHas('cars', array_merge(['id' => $car->id], $updateData));
    }

    public function test_user_cannot_update_others_car(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1, 'sanctum')->putJson("/api/cars/{$car->id}", ['color' => 'hacked']);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_their_car(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/cars/{$car->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
    }

    public function test_user_can_assign_car_and_job_is_dispatched(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => null]);

        $response = $this->actingAs($user, 'sanctum')->postJson("/api/cars/{$car->id}/assign");

        $response->assertStatus(200);
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'user_id' => $user->id,
        ]);

        Queue::assertPushed(CarAssignedJob::class, function ($job) use ($car, $user) {
            return $job->car->id === $car->id && $job->user->id === $user->id;
        });
    }

    public function test_user_can_get_brands(): void
    {
        Brand::factory()->count(3)->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/brands');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_get_car_models(): void
    {
        CarModel::factory()->count(5)->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/car_models');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }
}
