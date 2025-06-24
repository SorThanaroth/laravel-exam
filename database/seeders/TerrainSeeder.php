<?php

namespace Database\Seeders;

use App\Models\Terrain;
use App\Models\TerrainImage;
use Illuminate\Database\Seeder;

class TerrainSeeder extends Seeder
{
    public function run(): void
    {
        Terrain::factory(50)
            ->has(TerrainImage::factory()->count(3))
            ->create();
    }
}


