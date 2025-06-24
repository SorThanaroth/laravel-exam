// database/seeders/ReviewSeeder.php
<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        Review::factory(200)->create();
    }
}

