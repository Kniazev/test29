<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/brands",
     *     summary="Get a list of car brands",
     *     tags={"Brands"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BrandResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $brands = BrandResource::collection(Brand::all());

        return response()->json(['data' => $brands]);
    }
}
