<?php

namespace App\Repositories;

use App\Models\Car;

class CarRepository {
    public function all() { return Car::all(); }
    public function find($id) { return Car::findOrFail($id); }
    public function create(array $data) { return Car::create($data); }
    public function update(Car $car, array $data) { $car->update($data); return $car; }
    public function delete(Car $car) { return $car->delete(); }
}
