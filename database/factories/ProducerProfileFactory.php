<?php

namespace Database\Factories;

use App\Models\ProducerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProducerProfile>
 */
class ProducerProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'produsen']),
            'lokasi_desa' => fake()->streetName(),
            'kabupaten_kota' => fake()->city(),
            'komoditas_utama' => fake()->randomElement(['beras', 'jagung']),
            'status_verifikasi' => 'terverifikasi',
        ];
    }

    public function menunggu(): static
    {
        return $this->state(['status_verifikasi' => 'menunggu']);
    }

    public function ditolak(): static
    {
        return $this->state(['status_verifikasi' => 'ditolak']);
    }
}
