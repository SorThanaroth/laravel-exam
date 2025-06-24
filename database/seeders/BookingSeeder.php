// database/seeders/BookingSeeder.php
<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        Booking::factory(100)
            ->has(Payment::factory())
            ->create();
    }
}
