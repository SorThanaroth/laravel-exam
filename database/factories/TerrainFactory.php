<?php

namespace Database\Factories;

use App\Models\Terrain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TerrainFactory extends Factory
{
    protected $model = Terrain::class;

    public function definition(): array
    {
        $availableFrom = $this->faker->dateTimeBetween('now', '+1 month');
        $availableTo = $this->faker->dateTimeBetween($availableFrom, '+6 months');

        return [
            'owner_id' => User::factory(),
            'title' => $this->faker->words(3, true) . ' Terrain',
            'description' => $this->faker->paragraph(3),
            'location' => $this->faker->city . ', ' . $this->faker->state,
            'area_size' => $this->faker->randomFloat(2, 100, 10000), // 100 to 10,000 sq meters
            'price_per_day' => $this->faker->randomFloat(2, 50, 500), // $50 to $500 per day
            'available_from' => $availableFrom,
            'available_to' => $availableTo,
            'is_available' => $this->faker->boolean(85), // 85% chance of being available
            'main_image' => $this->faker->optional()->imageUrl(800, 600, 'nature'),
        ];
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => true,
        ]);
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }
}