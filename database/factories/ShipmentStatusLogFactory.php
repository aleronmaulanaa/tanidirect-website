<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ShipmentStatusLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShipmentStatusLog>
 */
class ShipmentStatusLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'status' => fake()->randomElement(['dipesan', 'diproses', 'dikirim', 'diterima']),
            'catatan' => fake()->optional()->sentence(),
            'diperbarui_pada' => now(),
        ];
    }
}
