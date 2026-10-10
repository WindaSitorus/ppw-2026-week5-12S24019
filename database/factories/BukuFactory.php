<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => $this->faker->isbn13(),
            'judul' => rtrim($this->faker->sentence(4), '.'),
            'penulis' => $this->faker->name(),
            'tahun_terbit' => $this->faker->year(),
            'stok' => $this->faker->numberBetween(5, 50),
            'kategori_id' => $this->faker->numberBetween(1, 4),
        ];
    }
}