<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\CarModel;
use Illuminate\Http\JsonResponse;

class ModelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/car_models",
     *     summary="Get a list of car models",
     *     tags={"Car Models"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ModelResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $models = ModelResource::collection(CarModel::all());

        return response()->json(['data' => $models]);
    }
}
