<?php

namespace App\Services;

use App\Jobs\CarAssignedJob;
use App\Models\Car;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CarService
{
    public function getCars(?User $user): LengthAwarePaginator
    {
        if ($user) {
            return $user->cars()->with(['brand', 'carModel'])->paginate();
        }
        return Car::with(['brand', 'carModel'])->paginate();
    }

    public function createCar(array $data): Car
    {
        $car = Car::create($data);
        $car->load(['brand', 'carModel']);
        return $car;
    }

    public function updateCar(Car $car, array $data): Car
    {
        $car->update($data);
        return $car;
    }

    public function deleteCar(Car $car): void
    {
        $car->delete();
    }

    public function assignCar(Car $car, User $user): void
    {
        $car->user()->associate($user);
        $car->save();

        CarAssignedJob::dispatch($car, $user);
    }

    public function unassignCar(Car $car): void
    {
        $car->user()->dissociate();
        $car->save();
    }
}
