<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Services\CarService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class CarController extends Controller
{
    use AuthorizesRequests;

    protected CarService $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    /**
     * @OA\Get(
     *     path="/api/cars",
     *     summary="Get a list of cars",
     *     tags={"Cars"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CarResource"))
     *     )
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $cars = $this->carService->getCars($request->user('sanctum'));

        return CarResource::collection($cars);
    }

    /**
     * @OA\Post(
     *     path="/api/cars",
     *     summary="Create a new car",
     *     tags={"Cars"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand_id", "car_model_id"},
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="car_model_id", type="integer", example=1),
     *             @OA\Property(property="year", type="integer", example=2022),
     *             @OA\Property(property="mileage", type="integer", example=50000),
     *             @OA\Property(property="color", type="string", example="blue")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Car created", @OA\JsonContent(ref="#/components/schemas/CarResource")),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): CarResource
    {
        try {
            $data = $request->validate([
                'brand_id' => 'required|exists:brands,id',
                'car_model_id' => 'required|exists:car_models,id',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'mileage' => 'nullable|integer|min:0',
                'color' => 'nullable|string|max:255',
            ]);

            $car = $this->carService->createCar($data);

            return new CarResource($car);
        } catch (Throwable $e) {
            Log::error('Failed to store car', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cars/{car}",
     *     summary="Get a specific car",
     *     tags={"Cars"},
     *     @OA\Parameter(name="car", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation", @OA\JsonContent(ref="#/components/schemas/CarResource")),
     *     @OA\Response(response=404, description="Car not found")
     * )
     */
    public function show(Car $car): CarResource
    {
        return new CarResource($car->load(['brand', 'carModel']));
    }

    /**
     * @OA\Put(
     *     path="/api/cars/{car}",
     *     summary="Update a car",
     *     tags={"Cars"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="car", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="brand_id", type="integer"),
     *             @OA\Property(property="car_model_id", type="integer"),
     *             @OA\Property(property="year", type="integer"),
     *             @OA\Property(property="mileage", type="integer"),
     *             @OA\Property(property="color", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Car updated", @OA\JsonContent(ref="#/components/schemas/CarResource")),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Car not found")
     * )
     */
    public function update(Request $request, Car $car): CarResource
    {
        try {
            $this->authorize('update', $car);

            $data = $request->validate([
                'brand_id' => 'sometimes|required|exists:brands,id',
                'car_model_id' => 'sometimes|required|exists:car_models,id',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'mileage' => 'nullable|integer|min:0',
                'color' => 'nullable|string|max:255',
            ]);

            $updatedCar = $this->carService->updateCar($car, $data);

            return new CarResource($updatedCar);
        } catch (Throwable $e) {
            Log::error('Failed to update car', ['car_id' => $car->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/cars/{car}",
     *     summary="Delete a car",
     *     tags={"Cars"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="car", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Car deleted"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Car not found")
     * )
     */
    public function destroy(Car $car): Response
    {
        try {
            $this->authorize('delete', $car);
            $this->carService->deleteCar($car);

            return response()->noContent();
        } catch (Throwable $e) {
            Log::error('Failed to delete car', ['car_id' => $car->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @OA\Post(
     *     path="/api/cars/{car}/assign",
     *     summary="Assign a car to the current user",
     *     tags={"Cars"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="car", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Car assigned"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Car not found")
     * )
     */
    public function assign(Request $request, Car $car): JsonResponse
    {
        try {
            $this->authorize('assign', $car);
            $this->carService->assignCar($car, $request->user());

            return response()->json(['message' => 'Car assigned successfully.']);
        } catch (Throwable $e) {
            Log::error('Failed to assign car', ['car_id' => $car->id, 'user_id' => $request->user()?->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @OA\Post(
     *     path="/api/cars/{car}/unassign",
     *     summary="Unassign a car from its user",
     *     tags={"Cars"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="car", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Car unassigned"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Car not found")
     * )
     */
    public function unassign(Car $car): JsonResponse
    {
        try {
            $this->authorize('unassign', $car);
            $this->carService->unassignCar($car);

            return response()->json(['message' => 'Car unassigned successfully.']);
        } catch (Throwable $e) {
            Log::error('Failed to unassign car', ['car_id' => $car->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
