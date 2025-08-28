<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toyota = Brand::where('name', 'Toyota')->first();
        $bmw = Brand::where('name', 'BMW')->first();
        $audi = Brand::where('name', 'Audi')->first();

        DB::table('car_models')->insert([
            ['brand_id' => $toyota->id, 'name' => 'Camry'],
            ['brand_id' => $toyota->id, 'name' => 'RAV4'],
            ['brand_id' => $bmw->id, 'name' => 'X5'],
            ['brand_id' => $bmw->id, 'name' => '3 Series'],
            ['brand_id' => $audi->id, 'name' => 'A6'],
            ['brand_id' => $audi->id, 'name' => 'Q7'],
        ]);
    }
}
