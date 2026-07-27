<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $jumlah = fake()->numberBetween(1, 50);

        return [
            'buyer_id' => User::factory(),
            'product_id' => Product::factory(),
            'order_pool_id' => null,
            'jumlah' => $jumlah,
            'total_harga' => $jumlah * fake()->randomFloat(2, 5000, 50000),
            'status_pengiriman' => 'dipesan',
        ];
    }

    public function diproses(): static
    {
        return $this->state(['status_pengiriman' => 'diproses']);
    }

    public function dikirim(): static
    {
        return $this->state(['status_pengiriman' => 'dikirim']);
    }

    public function diterima(): static
    {
        return $this->state(['status_pengiriman' => 'diterima']);
    }
}
