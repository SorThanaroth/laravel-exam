// database/seeders/FavoriteSeeder.php
<?php

namespace Database\Seeders;

use App\Models\Favorite;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        Favorite::factory(150)->create();
    }
}