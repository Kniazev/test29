<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarModel extends Model
{
    use HasFactory;

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
