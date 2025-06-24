<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $rating = $this->faker->numberBetween(1, 5);
        
        return [
            'terrain_id' => Terrain::factory(),
            'user_id' => User::factory(),
            'rating' => $rating,
            'comment' => $rating >= 4 
                ? $this->faker->randomElement([
                    'Excellent terrain! Perfect for our needs.',
                    'Great location and well-maintained area.',
                    'Highly recommend this terrain to others.',
                    'Amazing experience, will book again!',
                ])
                : ($rating >= 3 
                    ? $this->faker->randomElement([
                        'Good terrain overall, some minor issues.',
                        'Decent location but could be better maintained.',
                        'Average experience, nothing special.',
                    ])
                    : $this->faker->randomElement([
                        'Not as described, disappointed.',
                        'Poor condition, needs improvement.',
                        'Would not recommend this terrain.',
                    ])
                ),
        ];
    }

    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 5,
            'comment' => 'Excellent terrain! Perfect for our needs.',
        ]);
    }

    public function poor(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 1,
            'comment' => 'Not as described, disappointed.',
        ]);
    }
}