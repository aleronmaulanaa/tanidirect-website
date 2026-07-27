<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProducerProfile;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'buyer_id' => User::factory(),
            'producer_id' => ProducerProfile::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'komentar' => fake()->optional()->paragraph(),
        ];
    }
}
