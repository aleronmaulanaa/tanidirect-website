<?php

namespace Database\Factories;

use App\Models\OrderPool;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderPool>
 */
class OrderPoolFactory extends Factory
{
    public function definition(): array
    {
        $targetVolume = fake()->numberBetween(100, 1000);

        return [
            'product_id' => Product::factory(),
            'target_volume' => $targetVolume,
            'volume_terkumpul' => fake()->numberBetween(0, $targetVolume),
            'status' => 'open',
            'batas_waktu' => now()->addDays(fake()->numberBetween(3, 14)),
        ];
    }

    public function fulfilled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'volume_terkumpul' => $attributes['target_volume'],
                'status' => 'fulfilled',
            ];
        });
    }

    public function closed(): static
    {
        return $this->state(['status' => 'closed']);
    }
}
