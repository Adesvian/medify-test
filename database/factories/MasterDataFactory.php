<?php

namespace Database\Factories;

use App\Models\MasterItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterItem>
 */
class MasterDataFactory extends Factory
{
    protected $model = MasterItem::class;

    protected static $kode = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'kode' => str_pad(self::$kode++, 5, '0', STR_PAD_LEFT),
            'nama' => $this->faker->name(),
            'harga_beli' => rand(1, 100),
            'laba' => rand(10, 99),
            'supplier' => $this->faker->randomElement(['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu']),
            'jenis' => $this->faker->randomElement(['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK']),
        ];
    }
}
