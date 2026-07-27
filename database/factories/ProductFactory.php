<?php

namespace Database\Factories;

use App\Models\ProducerProfile;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'producer_id' => ProducerProfile::factory(),
            'kategori' => fake()->randomElement(['beras_medium', 'beras_premium', 'jagung']),
            'nama_produk' => fake()->words(3, true),
            'harga_jual' => fake()->randomFloat(2, 5000, 50000),
            'stok' => fake()->numberBetween(10, 500),
            'satuan' => 'kg',
            'deskripsi' => fake()->sentence(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
