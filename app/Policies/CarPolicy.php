<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Car $car): bool
    {
        if ($user === null) {
            return $car->user_id === null;
        }
        return $user->id === $car->user_id;
    }

    public function update(?User $user, Car $car): bool
    {
        if ($car->user_id === null) {
            return true;
        }

        if ($user === null) {
            return false;
        }
    
        return $user->id === $car->user_id;
    }

    public function delete(?User $user, Car $car): bool
    {
        if ($car->user_id === null) {
            return true;
        }

        if ($user === null) {
            return false;
        }
        
        return $user->id === $car->user_id;
    }

    public function assign(User $user, Car $car): bool
    {
        return true;
    }
    
    public function unassign(User $user, Car $car): bool
    {
        return $user->id === $car->user_id;
    }
}
