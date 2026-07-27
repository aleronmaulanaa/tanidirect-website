<?php

namespace Database\Factories;

use App\Models\PriceReference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceReference>
 */
class PriceReferenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kategori_komoditas' => fake()->randomElement(['beras_medium', 'beras_premium', 'jagung']),
            'kabupaten_kota' => fake()->city(),
            'sumber' => fake()->randomElement(['bps', 'siskaperbapo']),
            'tipe_harga' => fake()->randomElement(['produsen', 'konsumen']),
            'periode' => now()->format('Y-m'),
            'harga' => fake()->randomFloat(2, 5000, 50000),
        ];
    }
}
