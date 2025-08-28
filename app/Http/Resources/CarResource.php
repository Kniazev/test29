<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="CarResource",
 *     description="Car resource",
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="year", type="integer", example="2022"),
 *     @OA\Property(property="mileage", type="integer", example="50000"),
 *     @OA\Property(property="color", type="string", example="blue"),
 *     @OA\Property(property="user_id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="brand", ref="#/components/schemas/BrandResource"),
 *     @OA\Property(property="car_model", ref="#/components/schemas/ModelResource")
 * )
 */
class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'year' => $this->year,
            'mileage' => $this->mileage,
            'color' => $this->color,
            'brand_id' => $this->brand_id,
            'car_model_id' => $this->car_model_id,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'car_model' => new ModelResource($this->whenLoaded('carModel')),
            'user_id' => $this->user_id,
        ];
    }
}
