<?php

namespace Database\Factories;

use App\Models\OrderPool;
use App\Models\OrderPoolMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderPoolMember>
 */
class OrderPoolMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_pool_id' => OrderPool::factory(),
            'buyer_id' => User::factory(),
            'jumlah' => fake()->numberBetween(5, 100),
            'bergabung_pada' => now(),
        ];
    }
}
